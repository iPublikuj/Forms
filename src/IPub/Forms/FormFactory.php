<?php
/**
 * FormFactory.php
 *
 * @copyright      More in license.md
 * @license        https://www.ipublikuj.eu
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 * @package        iPublikuj:Forms!
 * @subpackage     common
 * @since          5.0
 *
 * @date           10.06.14
 */

declare(strict_types = 1);

namespace IPub\Forms;

use Nette\Application;
use Nette\DI;
use Nette\Localization;

/**
 * Form factory
 *
 * @package        iPublikuj:Forms!
 * @subpackage     common
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
final class FormFactory implements IFormFactory
{
	/**
	 * @var Localization\ITranslator|NULL
	 */
	private $translator;

	/**
	 * @var DI\Container
	 */
	private $container;

	/**
	 * @param Localization\ITranslator|NULL $translator
	 * @param DI\Container $container
	 */
	public function __construct(
		Localization\ITranslator $translator = NULL,
		DI\Container $container
	) {
		$this->translator = $translator;
		$this->container = $container;
	}

	/**
	 * {@inheritdoc}
	 */
	public function create(string $formClassName, ...$args) : Application\UI\Form
	{
		if (!class_exists($formClassName)) {
			throw new Exceptions\InvalidArgumentException('Factory form class isn\'t defined.');
		}

		/** @var Application\UI\Form $form */
		$form = $this->container->createInstance($formClassName, $args);
		$this->container->callInjects($form);

		if ($this->translator instanceof Localization\ITranslator) {
			$form->setTranslator($this->translator);
		}

		return $form;
	}
}
