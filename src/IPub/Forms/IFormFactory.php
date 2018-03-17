<?php
/**
 * IFormFactory.php
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:Forms!
 * @subpackage     common
 * @since          1.0.0
 *
 * @date           31.01.14
 */

declare(strict_types = 1);

namespace IPub\Forms;

/**
 * Form factory interface
 *
 * @package        iPublikuj:Forms!
 * @subpackage     common
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
interface IFormFactory
{
	/**
	 * @param string $formClassName
	 * @param array ...$args
	 *
	 * @return Application\UI\Form
	 */
	function create(string $formClassName, ...$args) : Application\UI\Form;
}
