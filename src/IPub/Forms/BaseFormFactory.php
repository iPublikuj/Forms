<?php
/**
 * BaseFormFactory.php
 *
 * @copyright	More in license.md
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:Forms!
 * @subpackage	common
 * @since		5.0
 *
 * @date		03.12.15
 */

namespace IPub\Forms;

use Nette;
use Nette\Localization;

use IPub;

/**
 * Base form factory
 *
 * @package		iPublikuj:Forms!
 * @subpackage	common
 *
 * @method onFormCreate(Nette\Application\UI\Form $form)
 */
class BaseFormFactory extends Nette\Object
{
	/**
	 * @var []
	 */
	public $onFormCreate;

	/**
	 * @var Localization\ITranslator
	 */
	public $translator;

	/**
	 * @var string
	 */
	protected $formClassName;

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

		return $this;
	}

	/**
	 * @return Nette\Application\UI\Form
	 */
	public function create()
	{
		$form = new $this->formClassName;

		if ($this->translator instanceof Localization\ITranslator) {
			$form->setTranslator($this->translator);
		}

		$this->onFormCreate($form);

		return $form;
	}
}