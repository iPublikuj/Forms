<?php
/**
 * FormProcessor.php
 *
 * @copyright	More in license.md
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:Forms!
 * @subpackage	Processors
 * @since		5.0
 *
 * @date		31.01.14
 */

namespace IPub\Forms\Processors;

use Nette;
use Nette\Application;
use Nette\Utils;

abstract class FormProcessor extends Nette\Object implements IFormProcessor
{
	/**
	 * Attach processor to form
	 *
	 * @param Application\UI\Form $form
	 *
	 * @return $this
	 */
	public function attach(Application\UI\Form $form)
	{
		$form->onSubmit[]	= [$this, 'submit'];
		$form->onSuccess[]	= [$this, 'success'];
		$form->onError[]	= [$this, 'error'];
		$form->onValidate[]	= [$this, 'validate'];

		return $this;
	}

	/**
	 * @param Application\UI\Form $form
	 * @param Utils\ArrayHash $values
	 */
	public function success(Application\UI\Form $form, Utils\ArrayHash $values) {}

	/**
	 * @param Application\UI\Form $form
	 */
	public function validate(Application\UI\Form $form) {}

	/**
	 * @param Application\UI\Form $form
	 */
	public function error(Application\UI\Form $form) {}

	/**
	 * @param Application\UI\Form $form
	 */
	public function submit(Application\UI\Form $form) {}
}