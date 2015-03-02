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
		'classicForm'	=> [
			'class'		=> '\IPub\Forms\Application\UI\Form',
			'factory'	=> '\IPub\Forms\FormFactory'
		],
		'entityForm'	=> [
			'class'		=> '\IPub\Forms\Application\UI\EntityForm',
			'factory'	=> '\IPub\Forms\EntityFormFactory',
		]
	];

	/**
	 * @return void
	 */
	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();

		foreach($config as $name => $definition) {
			Utils\Validators::assertField($definition, 'factory', 'string');

			$factory = $builder->addDefinition($this->prefix($name))
				->setClass($definition['factory'])
				->addTag('cms.forms');

			// Check if form class is defined
			if ($definition['class'] && class_exists($definition['class'])) {
				$factory
					->addSetup('setFormClass', [$definition['class']]);
			}
		}
	}
	
	public function beforeCompile()
	{
		$config = $this->getConfig($this->defaults);
		$builder = $this->getContainerBuilder();

		// Install extension latte macros
		$latteFactory = $builder->getDefinition($builder->getByType('\Nette\Bridges\ApplicationLatte\ILatteFactory') ?: 'nette.latteFactory');

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
