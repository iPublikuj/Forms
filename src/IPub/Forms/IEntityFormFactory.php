<?php
/**
 * IEntityFormFactory.php
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

use Nette;

interface IEntityFormFactory
{
	/**
	 * @return \Nette\Application\UI\Form
	 */
	function create();
}