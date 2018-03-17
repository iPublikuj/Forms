<?php
/**
 * Test: IPub\Forms\Extension
 * @testCase
 *
 * @copyright      More in license.md
 * @license        https://www.ipublikuj.eu
 * @author         Adam Kadlec https://www.ipublikuj.eu
 * @package        iPublikuj:Forms!
 * @subpackage     Tests
 * @since          1.0.0
 *
 * @date           04.02.15
 */

declare(strict_types = 1);

namespace IPubTests\Forms;

use Nette;
use Nette\Application\UI;

use Tester;
use Tester\Assert;

use IPub\Forms;

require __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'bootstrap.php';

class ExtensionTest extends Tester\TestCase
{
	public function testCompilersServices() : void
	{
		$dic = $this->createContainer();

		// Get classic form factory
		$factory = $dic->getService('extendedForms.factory');

		Assert::true($factory instanceof Forms\IFormFactory);
		Assert::true($factory->create(UI\Form::class) instanceof UI\Form);
	}

	/**
	 * @return Nette\DI\Container
	 */
	protected function createContainer() : Nette\DI\Container
	{
		$config = new Nette\Configurator();
		$config->setTempDirectory(TEMP_DIR);

		Forms\DI\FormsExtension::register($config);

		return $config->createContainer();
	}
}

\run(new ExtensionTest());
