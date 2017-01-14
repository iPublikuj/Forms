<?php
/**
 * TForm.php
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:Forms!
 * @subpackage     Forms
 * @since          1.0.0
 *
 * @date           26.05.13
 */

namespace IPub\Forms\Forms;

use Nette;
use Nette\Utils;

use IPub\Forms;

/**
 * Form trait for decorating form
 *
 * @package        iPublikuj:Forms!
 * @subpackage     Forms
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
trait TForm
{
	/**
	 * @var mixed
	 */
	protected $id;

	/**
	 * @var string|NULL
	 */
	protected $errorClass = NULL;

	/**
	 * @param array $defaults
	 */
	public function restore(array $defaults = [])
	{
		$this->setDefaults($defaults, TRUE);
		$this->setValues($defaults, TRUE);
	}

	/**
	 * @return mixed
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @param mixed $id
	 */
	public function setId($id)
	{
		$this->id = $id;
	}

	/**
	 * @param string $errorClass
	 *
	 * @return void
	 */
	public function setErrorClass(string $errorClass)
	{
		$this->errorClass = $errorClass;
	}

	/**
	 * @return void
	 */
	protected function beforeRender()
	{
		parent::beforeRender();

		/** @var Nette\Forms\Controls\BaseControl $control */
		foreach ($this->getControls() as $control) {
			$inputClass = [];

			if ($control->isRequired()) {
				$control->getLabelPrototype()->appendAttribute('required', 'required');
				$control->getLabelPrototype()->appendAttribute('class', 'ipub-field-required');

				$inputClass[] = 'ipub-field-required';
			}

			if ($control->hasErrors()) {
				$inputClass[] = 'ipub-field-error';

				if ($this->errorClass) {
					$inputClass[] = $this->errorClass;
				}
			}

			$control->getControlPrototype()->addAttributes(['class' => $inputClass]);
		}
	}

	/**
	 * @param string $name
	 * @param string $class
	 *
	 * @return void
	 */
	protected function addExtension(string $name, string $class)
	{
		Nette\Forms\Container::extensionMethod($name, function (Nette\Forms\Container $container, $name, $label = NULL) use ($class) {
			return $container[$name] = new $class($label);
		});
	}
}
