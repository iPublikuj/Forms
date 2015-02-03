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
use Nette\Utils;

use IPub\Forms;

class Form extends Nette\Application\UI\Form
{
	/**
	 * @var int
	 */
	protected $id;

	/**
	 * Set form processor
	 *
	 * @param Forms\IFormProcessor $processor
	 *
	 * @return $this
	 */
	public function addProcessor(Forms\IFormProcessor $processor)
	{
		$processor->attach($this);

		return $this;
	}

	/**
	 * @param $name
	 * @param $class
	 */
	protected function addExtension($name, $class)
	{
		Nette\Forms\Container::extensionMethod($name, function (Nette\Forms\Container $container, $name, $label = NULL) use ($class){
			return $container[$name] = new $class($label);
		});
	}

	/**
	 * @param array $defaults
	 *
	 * @return $this
	 */
	public function restore(array $defaults = [])
	{
		$this->setDefaults($defaults, TRUE);
		$this->setValues($defaults, TRUE);

		return $this;
	}

	/**
	 * @param bool $asArray
	 *
	 * @return array|Utils\ArrayHash
	 */
	public function getValues($asArray = FALSE)
	{
		$values = (array) parent::getValues(TRUE);

		if (!isset($values['id']) && $this->getId() != NULL) {
			$values['id'] = $this->getId();
		}

		return $asArray ? $values : Utils\ArrayHash::from($values);
	}

	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @param int $id
	 *
	 * @return $this
	 */
	public function setId($id)
	{
		$this->id = (int) $id;

		return $this;
	}
}