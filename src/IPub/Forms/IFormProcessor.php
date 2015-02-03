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

use Nette\Application;

interface IFormProcessor
{
	/**
	 * Attach processor to form
	 *
	 * @param Application\UI\Form $form
	 *
	 * @return $this
	 */
	public function attach(Application\UI\Form $form);
}