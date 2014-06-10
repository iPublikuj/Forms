<?php
/**
 * FormsExtension.php
 *
 * @copyright	More in license.md
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:Forms!
 * @subpackage	common
 * @since		5.0
 *
 * @date		31.01.14
 */

namespace IPub\Forms\DI;

use Nette;
use Nette\DI\Compiler;
use Nette\DI\Configurator;
use Nette\PhpGenerator as Code;
use Nette\Utils;

if (!class_exists('Nette\DI\CompilerExtension')) {
	class_alias('Nette\Config\CompilerExtension', 'Nette\DI\CompilerExtension');
	class_alias('Nette\Config\Compiler', 'Nette\DI\Compiler');
	class_alias('Nette\Config\Helpers', 'Nette\DI\Config\Helpers');
}

if (isset(Nette\Loaders\NetteLoader::getInstance()->renamed['Nette\Configurator']) || !class_exists('Nette\Configurator')) {
	unset(Nette\Loaders\NetteLoader::getInstance()->renamed['Nette\Configurator']);
	class_alias('Nette\Config\Configurator', 'Nette\Configurator');
}

class FormsExtension extends Nette\DI\CompilerExtension
{
	/**
	 * @var array
	 */
	public $defaults = array(
		'classicFormClass'	=> '\IPub\Forms\Application\UI\Form',
		'entityFormClass'	=> '\IPub\Forms\Application\UI\EntityForm'
	);

	/**
	 * @return void
	 */
	public function loadConfiguration()
	{
		$container = $this->getContainerBuilder();
		$config = $this->getConfig($this->defaults);

		Utils\Validators::assertField($config, 'classicFormClass', 'string');
		Utils\Validators::assertField($config, 'entityFormClass', 'string');

		$container->addDefinition($this->prefix('formFactory'))
			->setClass('IPub\Forms\FormFactory')
			->addSetup('setFormClass', array($config['classicFormClass']))
			->addTag('cms.forms');

		$container->addDefinition($this->prefix('entityFormFactory'))
			->setClass('IPub\Forms\EntityFormFactory')
			->addSetup('setFormClass', array($config['entityFormClass']))
			->addTag('cms.forms');

		// Install extension latte macros
		$install = 'IPub\Forms\Latte\Macros::install';
		$container->getDefinition('nette.latte')
			->addSetup($install . '(?->getCompiler())', array('@self'));
	}
}