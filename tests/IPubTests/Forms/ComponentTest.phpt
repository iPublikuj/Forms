<?php
/**
 * Test: IPub\Forms\Compiler
 * @testCase
 *
 * @copyright	More in license.md
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:Forms!
 * @subpackage	Tests
 * @since		5.0
 *
 * @date		30.01.15
 */

namespace IPubTests\Forms;

use Nette;
use Nette\Application;
use Nette\Application\Routers;
use Nette\Application\UI;
use Nette\Utils;

use Tester;
use Tester\Assert;

use IPub;
use IPub\Forms;

require __DIR__ . '/../bootstrap.php';

class ComponentTest extends Tester\TestCase
{
	/**
	 * @var Nette\Application\IPresenterFactory
	 */
	private $presenterFactory;

	/**
	 * @var \SystemContainer|\Nette\DI\Container
	 */
	private $container;

	public function dataFormValues()
	{
		return [
			['John Doe', 'jdoe', '123456'],
			['Jane Doe', 'janedoe', '657987'],
			['Tester', 'someusername', NULL],
		];
	}

	public function dataFormInvalidValues()
	{
		return [
			['John Doe', NULL, '123456', 'This field is required.'],
			[NULL, 'username', '123456', 'User full name is required.'],
		];
	}

	/**
	 * Set up
	 */
	public function setUp()
	{
		parent::setUp();

		$this->container = $this->createContainer();

		// Get presenter factory from container
		$this->presenterFactory = $this->container->getByType('Nette\Application\IPresenterFactory');
	}

	public function testCreatingForm()
	{
		// Create test presenter
		$presenter = $this->createPresenter();

		// Create GET request
		$request = new Application\Request('Test', 'GET', array('action' => 'default'));
		// & fire presenter & catch response
		$response = $presenter->run($request);

		$dq = Tester\DomQuery::fromHtml((string) $response->getSource());

		Assert::true($dq->has('input[name="username"]'));
		Assert::true($dq->has('input[name="password"]'));
		Assert::true($dq->has('input[name="name"]'));
	}

	/**
	 * @dataProvider dataFormValues
	 *
	 * @param string $name
	 * @param string $username
	 * @param string $password
	 */
	public function testProcessingForm($name, $username, $password)
	{
		// Create test presenter
		$presenter = $this->createPresenter();

		// Create GET request
		$request = new Application\Request('Test', 'POST', ['action' => 'process'], [
			'do'		=> 'userForm-submit',
			'name'		=> $name,
			'username'	=> $username,
			'password'	=> $password
		]);
		// & fire presenter & catch response
		$response = $presenter->run($request);

		Assert::equal('Username:'. $username .'|Password:'. $password .'|Name:'. $name, (string) $response->getSource());
	}

	/**
	 * @dataProvider dataFormInvalidValues
	 *
	 * @param string $name
	 * @param string $username
	 * @param string $password
	 * @param string $expected
	 */
	public function testInvalidProcessingForm($name, $username, $password, $expected)
	{
		// Create test presenter
		$presenter = $this->createPresenter();

		// Create GET request
		$request = new Application\Request('Test', 'POST', ['action' => 'process'], [
			'do'		=> 'userForm-submit',
			'name'		=> $name,
			'username'	=> $username,
			'password'	=> $password
		]);
		// & fire presenter & catch response
		$response = $presenter->run($request);

		Assert::equal($expected, (string) $response->getSource());
	}

	/**
	 * @return Application\IPresenter
	 */
	protected function createPresenter()
	{
		// Create test presenter
		$presenter = $this->presenterFactory->createPresenter('Test');
		// Disable auto canonicalize to prevent redirection
		$presenter->autoCanonicalize = FALSE;

		return $presenter;
	}

	/**
	 * @return \SystemContainer|\Nette\DI\Container
	 */
	protected function createContainer()
	{
		$config = new Nette\Configurator();
		$config->setTempDirectory(TEMP_DIR);

		Forms\DI\FormsExtension::register($config);

		$config->addConfig(__DIR__ . '/files/presenters.neon', $config::NONE);

		return $config->createContainer();
	}
}

class TestPresenter extends UI\Presenter
{
	/**
	 * @var Forms\IFormFactory
	 */
	protected $factory;

	public function renderDefault()
	{
		// Set template for component testing
		$this->template->setFile(__DIR__ . DIRECTORY_SEPARATOR .'templates'. DIRECTORY_SEPARATOR .'default.latte');
	}

	public function renderProcess()
	{
		// Get all flashes
		$flashes = $this->getTemplate()->flashes;
		// Get first flash message
		$flash = reset($flashes);

		$this->sendResponse(new Application\Responses\TextResponse($flash->message));
	}

	/**
	 * @param Forms\IFormFactory $factory
	 */
	public function injectFormFactory(Forms\IFormFactory $factory)
	{
		$this->factory = $factory;
	}

	/**
	 * Create confirmation dialog
	 *
	 * @return UI\Form
	 */
	protected function createComponentUserForm()
	{
		// Init form object
		$form = $this->factory->create();

		$form->addText('username', 'Username')
			->setRequired('This field is required.');

		$form->addPassword('password', 'Password');

		$form->addText('name', 'Name')
			->setRequired('User full name is required.');

		// Attach processor
		$form->addProcessor(new CustomFormProcessor());

		return $form;
	}
}

class CustomFormProcessor extends Forms\Processors\FormProcessor
{
	public function success(UI\Form $form, Utils\ArrayHash $values)
	{
		$form->getPresenter()->flashMessage('Username:'. $values->username .'|Password:'. $values->password .'|Name:'. $values->name);
	}

	public function error(UI\Form $form)
	{
		foreach($form->getErrors() as $error)
		{
			$form->getPresenter()->flashMessage($error, 'error');
		}
	}
}

class RouterFactory
{
	/**
	 * @return \Nette\Application\IRouter
	 */
	public static function createRouter()
	{
		$router = new Routers\  RouteList();
		$router[] = new Routers\Route('<presenter>/<action>[/<id>]', 'Test:default');

		return $router;
	}
}

\run(new ComponentTest());
