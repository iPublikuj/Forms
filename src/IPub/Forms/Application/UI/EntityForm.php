<?php
/**
 * Form.php
 *
 * @copyright	More in license.md
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:Forms!
 * @subpackage	Application
 * @since		5.0
 *
 * @date		26.05.13
 */

namespace IPub\Forms\Application\UI;

use Nette;
use Kdyby\DoctrineForms;

class EntityForm extends Form
{
	/**
	 * Implement DoctrineForms
	 */
	use DoctrineForms\EntityForm;
}