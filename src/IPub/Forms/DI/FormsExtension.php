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
use Nette\DI;
use Nette\PhpGenerator as Code;
use Nette\Utils;

class FormsExtension extends DI\CompilerExtension
{
	/**
	 * @var array
	 */
	protected $defaults = [
		'classicFormClass'	=> '\IPub\Forms\Application\UI\Form',
		'entityFormClass'	=> '\IPub\Forms\Application\UI\EntityForm'
	];

	/**
	 * @return void
	 */
	public function loadConfiguration()
	{
		$config = $this->getConfig($this->defaults);
		$builder = $this->getContainerBuilder();

		Utils\Validators::assertField($config, 'classicFormClass', 'string');
		Utils\Validators::assertField($config, 'entityFormClass', 'string');

		$builder->addDefinition($this->prefix('formFactory'))
			->setClass('IPub\Forms\FormFactory')
			->addSetup('setFormClass', [$config['classicFormClass']])
			->addTag('cms.forms');

		$builder->addDefinition($this->prefix('entityFormFactory'))
			->setClass('IPub\Forms\EntityFormFactory')
			->addSetup('setFormClass', [$config['entityFormClass']])
			->addTag('cms.forms');

		// Install extension latte macros
		$latteFactory = $builder->hasDefinition('nette.latteFactory')
			? $builder->getDefinition('nette.latteFactory')
			: $builder->getDefinition('nette.latte');

		$latteFactory
			->addSetup('IPub\Forms\Latte\Macros::install(?->getCompiler())', ['@self']);
	}

	/**
	 * @param Nette\Configurator $config
	 * @param string $extensionName
	 */
	public static function register(Nette\Configurator $config, $extensionName = 'forms')
	{
		$config->onCompile[] = function (Nette\Configurator $config, Nette\DI\Compiler $compiler) use ($extensionName) {
			$compiler->addExtension($extensionName, new FormsExtension());
		};
	}
}