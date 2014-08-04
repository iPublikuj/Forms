<?php
/**
 * FormProcessor.php
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

namespace IPub\Forms;

use Nette\Application\UI\Form;
use Nette\Object;

abstract class FormProcessor extends Object implements IFormProcessor
{
	/**
	 * Attach processor to form
	 *
	 * @param Form $form
	 *
	 * @return $this
	 */
	public function attach(Form $form)
	{
		$form->onSubmit[]	= $this->submit;
		$form->onSuccess[]	= $this->success;
		$form->onError[]	= $this->error;
		$form->onValidate[]	= $this->validate;

		return $this;
	}

	/**
	 * @param Form $form
	 * @param array $values
	 */
	public function success(Form $form, $values) {}

	/**
	 * @param Form $form
	 */
	public function validate(Form $form) {}

	/**
	 * @param Form $form
	 */
	public function error(Form $form) {}

	/**
	 * @param Form $form
	 */
	public function submit(Form $form) {}
}