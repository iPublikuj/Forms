<?php
/**
 * Test: IPub\Forms\Compiler
 * @testCase
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:Forms!
 * @subpackage     Tests
 * @since          1.0.0
 *
 * @date           30.01.15
 */

declare(strict_types = 1);

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

require __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'bootstrap.php';

class ComponentTest extends Tester\TestCase
{
	/**
	 * @var Application\IPresenterFactory
	 */
	private $presenterFactory;

	/**
	 * @var Nette\DI\Container
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
	 * @return void
	 */
	public function setUp()
	{
		parent::setUp();

		$this->container = $this->createContainer();

		// Get presenter factory from container
		$this->presenterFactory = $this->container->getByType(Application\IPresenterFactory::class);
	}

	public function testCreatingForm()
	{
		// Create test presenter
		$presenter = $this->createPresenter();

		// Create GET request
		$request = new Application\Request('Test', 'GET', ['action' => 'default']);
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
			'do'       => 'userForm-submit',
			'name'     => $name,
			'username' => $username,
			'password' => $password
		]);
		// & fire presenter & catch response
		$response = $presenter->run($request);

		Assert::equal('Username:' . $username . '|Password:' . $password . '|Name:' . $name, (string) $response->getSource());
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
			'do'       => 'userForm-submit',
			'name'     => $name,
			'username' => $username,
			'password' => $password
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
	 * @return Nette\DI\Container
	 */
	protected function createContainer() : Nette\DI\Container
	{
		$config = new Nette\Configurator();
		$config->setTempDirectory(TEMP_DIR);

		Forms\DI\FormsExtension::register($config);

		$config->addConfig(__DIR__ . DS . 'files' . DS . 'presenters.neon');

		return $config->createContainer();
	}
}

class TestPresenter extends UI\Presenter
{
	/**
	 * @var Forms\IFormFactory
	 */
	protected $factory;

	/**
	 * @return void
	 */
	public function renderDefault()
	{
		// Set template for component testing
		$this->template->setFile(__DIR__ . DS . 'templates' . DS . 'default.latte');
	}

	/**
	 * @return void
	 */
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
		$form = $this->factory->create(UI\Form::class);

		$form->addText('username', 'Username')
			->setRequired('This field is required.');

		$form->addPassword('password', 'Password');

		$form->addText('name', 'Name')
			->setRequired('User full name is required.');

		$form->onSuccess[] = [$this, 'formSuccess'];
		$form->onError[] = [$this, 'formError'];

		return $form;
	}

	/**
	 * @return void
	 */
	public function formSuccess(UI\Form $form, Utils\ArrayHash $values)
	{
		$form->getPresenter()->flashMessage('Username:' . $values->username . '|Password:' . $values->password . '|Name:' . $values->name);
	}

	/**
	 * @return void
	 */
	public function formError(UI\Form $form)
	{
		foreach ($form->getErrors() as $error) {
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
		$router = new Routers\RouteList();
		$router[] = new Routers\Route('<presenter>/<action>[/<id>]', 'Test:default');

		return $router;
	}
}

\run(new ComponentTest());
