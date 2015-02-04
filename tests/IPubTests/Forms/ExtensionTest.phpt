<?php
/**
 * Test: IPub\Forms\Extension
 * @testCase
 *
 * @copyright	More in license.md
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:Forms!
 * @subpackage	Tests
 * @since		5.0
 *
 * @date		04.02.15
 */

namespace IPubTests\Forms;

use Nette;

use Tester;
use Tester\Assert;

use IPub;
use IPub\Forms;

require __DIR__ . '/../bootstrap.php';

class ExtensionTest extends Tester\TestCase
{
	/**
	 * @return \SystemContainer|\Nette\DI\Container
	 */
	protected function createContainer()
	{
		$config = new Nette\Configurator();
		$config->setTempDirectory(TEMP_DIR);

		Forms\DI\FormsExtension::register($config);

		return $config->createContainer();
	}

	public function testCompilersServices()
	{
		$dic = $this->createContainer();

		// Get classic form factory
		$factory = $dic->getService('forms.classicForm');

		Assert::true($factory instanceof IPub\Forms\IFormFactory);
		Assert::true($factory->create() instanceof IPub\Forms\Application\UI\Form);

		// Get entity form factory
		$factory = $dic->getService('forms.entityForm');

		Assert::true($factory instanceof IPub\Forms\IEntityFormFactory);
		Assert::true($factory->create() instanceof IPub\Forms\Application\UI\EntityForm);
	}
}

\run(new ExtensionTest());