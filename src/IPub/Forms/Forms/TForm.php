<?php
/**
 * TForm.php
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

namespace IPub\Forms\Forms;

use Nette;
use Nette\Utils;

use IPub\Forms;
use IPub\Forms\Processors;

trait TForm
{
	/**
	 * @var mixed
	 */
	protected $id;

	/**
	 * @param string $name
	 * @param string $class
	 */
	protected function addExtension(string $name, string $class)
	{
		Nette\Forms\Container::extensionMethod($name, function (Nette\Forms\Container $container, $name, $label = NULL) use ($class) {
			return $container[$name] = new $class($label);
		});
	}

	/**
	 * @param array $defaults
	 */
	public function restore(array $defaults = [])
	{
		$this->setDefaults($defaults, TRUE);
		$this->setValues($defaults, TRUE);
	}

	/**
	 * @return mixed
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @param mixed $id
	 */
	public function setId($id)
	{
		$this->id = $id;
	}
}
