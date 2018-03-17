<?php
/**
 * FormsExtension.php
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:Forms!
 * @subpackage     common
 * @since          1.0.0
 *
 * @date           31.01.14
 */

declare(strict_types = 1);

namespace IPub\Forms\DI;

use Nette;
use Nette\Bridges;
use Nette\DI;

use IPub\Forms;

/**
 * Form extension container
 *
 * @package        iPublikuj:Forms!
 * @subpackage     DI
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
final class FormsExtension extends DI\CompilerExtension
{
	/**
	 * @return void
	 */
	public function loadConfiguration() : void
	{
		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('factory'))
			->setType(Forms\FormFactory::class)
			->addTag('cms.forms');
	}

	/**
	 * {@inheritdoc}
	 */
	public function beforeCompile() : void
	{
		parent::beforeCompile();

		$builder = $this->getContainerBuilder();

		// Install extension latte macros
		$latteFactory = $builder->getDefinition($builder->getByType(Bridges\ApplicationLatte\ILatteFactory::class) ?: 'nette.latteFactory');

		$latteFactory->addSetup('IPub\Forms\Latte\Macros::install(?->getCompiler())', ['@self']);
	}

	/**
	 * @param Nette\Configurator $config
	 * @param string $extensionName
	 *
	 * @return void
	 */
	public static function register(Nette\Configurator $config, string $extensionName = 'extendedForms') : void
	{
		$config->onCompile[] = function (Nette\Configurator $config, Nette\DI\Compiler $compiler) use ($extensionName) {
			$compiler->addExtension($extensionName, new FormsExtension());
		};
	}
}
