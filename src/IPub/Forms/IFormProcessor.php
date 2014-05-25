<?php
/**
 * IFormProcessor.php
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

interface IFormProcessor
{
	/**
	 * Attach processor to form
	 *
	 * @param Form $form
	 *
	 * @return $this
	 */
	public function attach(Form $form);
}