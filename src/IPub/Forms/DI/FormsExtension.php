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

use Nette\DI;
use Nette\Diagnostics\Debugger;
use Nette\Utils;

class FormsExtension extends DI\CompilerExtension
{
	/**
	 * @var array
	 */
	public $defaults = array(
		'class' => 'Nette\Application\UI\Form'
	);

	/**
	 * @return void
	 */
	public function loadConfiguration()
	{
		$container = $this->getContainerBuilder();
		$config = $this->getConfig($this->defaults);

		Utils\Validators::assertField($config, 'class', 'string');

		$container->addDefinition($this->prefix('formFactory'))
			->setClass($config['class'])
			->setImplement('IPub\Forms\IFormFactory')
			->setInject(TRUE)
			->addTag('cms.forms');

		// Install extension latte macros
		$install = 'IPub\Forms\Latte\Macros::install';
		$container->getDefinition('nette.latte')
			->addSetup($install . '(?->getCompiler())', array('@self'));
	}
}