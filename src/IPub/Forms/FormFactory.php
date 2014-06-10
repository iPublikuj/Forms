<?php
/**
 * FormFactory.php
 *
 * @copyright	More in license.md
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:Forms!
 * @subpackage	common
 * @since		5.0
 *
 * @date		10.06.14
 */

namespace IPub\Forms;

use Nette;
use Nette\Localization;

class FormFactory extends Nette\Object implements IFormFactory
{
	/** @var [] */
	public $onFormCreate;

	/**
	 * @var Localization\ITranslator
	 */
	public $translator;

	/**
	 * @var string
	 */
	protected $formClassName = '\IPub\Forms\Application\UI\Form';

	/**
	 * @param Localization\ITranslator $translator
	 */
	public function __construct(Localization\ITranslator $translator = NULL)
	{
		$this->translator = $translator;
	}

	/**
	 * @param string $className
	 *
	 * @return $this
	 *
	 * @throws Nette\InvalidArgumentException
	 */
	public function setFormClass($className)
	{
		if (!class_exists($className)) {
			throw new Nette\InvalidArgumentException('Provided form class name "'. $className .'" doesn\'t exists.');
		}

		$this->formClassName = $className;

		return $this;
	}

	/**
	 * @return Nette\Application\UI\Form
	 */
	public function create()
	{
		$form = new $this->formClassName;

		if ($this->translator) {
			$form->setTranslator($this->translator);
		}

		$this->onFormCreate($form);

		return $form;
	}
}