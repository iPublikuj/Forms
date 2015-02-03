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
		$config = $this->getConfig($this->defaults);
		$builder = $this->getContainerBuilder();

		foreach($config as $name => $definition) {
			Utils\Validators::assertField($definition, 'class', 'string');
			Utils\Validators::assertField($definition, 'factory', 'string');

			$builder->addDefinition($this->prefix($name))
				->setClass($definition['factory'])
				->addSetup('setFormClass', [$definition['class']])
				->addTag('cms.forms');
		}

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