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

use Nette;
use Nette\Application;

/**
 * Form factory interface
 *
 * @package        iPublikuj:Forms!
 * @subpackage     common
 */
interface IFormFactory
{
	/**
	 * Define class name
	 */
	const INTERFACE_NAME = __CLASS__;

	/**
	 * @param string $formClassName
	 * @param array ...$args
	 *
	 * @return mixed
	 */
	function create(string $formClassName, ...$args);
}
