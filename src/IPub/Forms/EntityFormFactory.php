<?php
/**
 * EntityFormFactory.php
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

use IPub;

/**
 * Entity form factory
 *
 * @package		iPublikuj:Forms!
 * @subpackage	common
 *
 * @method onFormCreate(Nette\Application\UI\Form $form)
 */
class EntityFormFactory extends BaseFormFactory implements IEntityFormFactory
{
	/**
	 * @var string
	 */
	protected $formClassName = '\IPub\Forms\Application\UI\EntityForm';
}