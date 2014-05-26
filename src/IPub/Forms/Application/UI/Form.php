<?php
/**
 * Form.php
 *
 * @copyright	More in license.md
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:AppModule!
 * @subpackage	Application
 * @since		5.0
 *
 * @date		26.05.13
 */

namespace IPub\Forms\Application\UI;

use Nette;
use Nette\Localization;

use IPub\Forms;

class Form extends Nette\Application\UI\Form
{
	/**
	 * @var int
	 */
	protected $id;

	/**
	 * @param Localization\ITranslator $translator
	 *
	 * @return $this
	 */
	public function injectTranslator(Localization\ITranslator $translator)
	{
		$this->setTranslator($translator);

		return $this;
	}

	/**
	 * Set form processor
	 *
	 * @param Forms\IFormProcessor $processor
	 *
	 * @return $this
	 */
	public function addProcessor(Forms\IFormProcessor $processor = NULL)
	{
		if ($processor !== NULL) {
			$processor->attach($this);
		}

		return $this;
	}

	/**
	 * @param $name
	 * @param $class
	 */
	protected function addExtension($name, $class)
	{
		Container::extensionMethod($name, function (Nette\Forms\Container $container, $name, $label = NULL) use ($class){
			return $container[$name] = new $class($label);
		});
	}

	/**
	 * @param array $defaults
	 */
	public function restore(array $defaults = array())
	{
		$this->setDefaults($defaults, TRUE);
		$this->setValues($defaults, TRUE);
	}

	/**
	 * @param bool $asArray
	 *
	 * @return array|\Nette\ArrayHash
	 */
	public function getValues($asArray = false)
	{
		$values = (array) parent::getValues($asArray);

		if (!isset($values['id'])) {
			$values['id'] = $this->getId();
		}

		return Nette\ArrayHash::from($values);
	}

	/**
	 * @param array|\Nette\Forms\Traversable $values
	 * @param bool $erase
	 *
	 * @return \Nette\Forms\Container
	 */
	public function setDefaults($values, $erase = false)
	{
		// Set form ID
		if (is_object($values) && isset($values->id)) {
			$this->setId($values->id);

		} else if (is_array($values) && isset($values['id'])) {
			$this->setId($values['id']);
		}

		// Get object to string for values compatibility
		if (is_array($values) && count($values)) {
			$values = array_map(function ($value) {
				if (is_object($value) && (method_exists($value, '__toString'))) {
					if (isset($value->id)) {
						return (string)$value->id;

					} else {
						return (string)$value;
					}
				}

				return $value;
			}, $values);
		}

		return parent::setDefaults($values, $erase);
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