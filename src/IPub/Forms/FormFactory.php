<?php
/**
 * FormFactory.php
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
use Nette\Forms;
use Nette\Localization;

class FormFactory extends Nette\Object implements IFormFactory
{
	/**
	 * @var  string
	 */
	private $class = 'Nette\Application\UI\Form';

	/**
	 * @var Localization\ITranslator
	 */
	private $translator;

	/**
	 * @var Forms\IFormRenderer
	 */
	private $renderer;

	/**
	 * @var  array|IFormProcessor[]
	 */
	private $processors = array();

	/**
	 * @param $class
	 * 
	 * @return $this
	 * 
	 * @throws Nette\InvalidArgumentException
	 */
	public function setFormClass($class)
	{
		if (!class_exists($class)) {
			throw new Nette\InvalidArgumentException('Given class "' . $this->class . '" not found.');
		}

		$this->class = (string) $class;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getFormClass()
	{
		return $this->class;
	}

	/**
	 * Set translate adapter
	 *
	 * @param Localization\ITranslator $translator
	 * 
	 * @return $this
	 */
	public function setTranslator(Localization\ITranslator $translator = NULL)
	{
		$this->translator = $translator;

		return $this;
	}

	/**
	 * Sets form renderer
	 *
	 * @param Forms\IFormRenderer $renderer
	 * 
	 * @return $this
	 */
	public function setRenderer(Forms\IFormRenderer $renderer = NULL)
	{
		$this->renderer = $renderer;

		return $this;
	}

	/**
	 * Set form processor
	 *
	 * @param IFormProcessor $processor
	 *
	 * @return $this
	 */
	public function addProcessor(IFormProcessor $processor)
	{
		$this->processors[spl_object_hash($processor)] = $processor;

		return $this;
	}

	/**
	 * Create base Form
	 *
	 * @param Nette\ComponentModel\IContainer $parent
	 * @param string $name
	 *
	 * @return \Nette\Application\UI\Form
	 *
	 * @throws \Nette\InvalidArgumentException
	 */
	public function create(Nette\ComponentModel\IContainer $parent = NULL, $name = NULL)
	{
		if (!class_exists($this->class)) {
			throw new Nette\InvalidArgumentException('Given class "' . $this->class . '" not found.');
		}

		/**
		 * @var \Nette\Application\UI\Form $form
		 */
		$form = new $this->class($parent, $name);

		if (!$form instanceof Nette\Application\UI\Form) {
			throw new Nette\InvalidArgumentException('Class "' . $this->class . '" is not instance of Nette\Application\UI\Form.');
		}

		$form
			->setTranslator($this->translator)
			->setRenderer($this->renderer);

		foreach ($this->processors as $processor) {
			$processor->attach($form);
		}

		return $form;
	}

	/**
	 * Get list of form processors
	 *
	 * @return array|IFormProcessor[]
	 */
	public function getProcessors()
	{
		return $this->processors;
	}

	/**
	 * Remove all or specific processors
	 *
	 * @param array $processors
	 *
	 * @return $this
	 */
	public function removeProcessors(array $processors = array())
	{
		if (count($processors)) {
			foreach ($processors as $processor) {
				if (isset($this->processors[$processor])) {
					unset($this->processors[$processor]);
				}
			}

		}else {
			$this->processors = array();
		}

		return $this;
	}
}