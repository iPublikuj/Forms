<?php
/**
 * Form.php
 *
 * @copyright      More in license.md
 * @license        https://www.ipublikuj.eu
 * @author         Adam Kadlec https://www.ipublikuj.eu
 * @package        iPublikuj:Forms!
 * @subpackage     Forms
 * @since          1.0.0
 *
 * @date           26.05.13
 */

declare(strict_types = 1);

namespace IPub\Forms\Forms;

use Nette\Application;

/**
 * Classic form
 *
 * @package        iPublikuj:Forms!
 * @subpackage     Forms
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
class Form extends Application\UI\Form
{
	/**
	 * Implement form methods
	 */
	use TForm;
}
