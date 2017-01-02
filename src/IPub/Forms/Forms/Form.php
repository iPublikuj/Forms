<?php
/**
 * Form.php
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

declare(strict_types = 1);

namespace IPub\Forms\Forms;

use Nette;
use Nette\Application;

class Form extends Application\UI\Form
{
	/**
	 * Implement form methods
	 */
	use TForm;
}
