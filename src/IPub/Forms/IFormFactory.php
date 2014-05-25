<?php
/**
 * IFormFactory.php
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

interface IFormFactory
{
	/**
	 * @param string $class
	 *
	 * @return $this
	 */
	public function setFormClass($class);

	/**
	 * @return string
	 */
	public function getFormClass();

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
	public function create(Nette\ComponentModel\IContainer $parent = NULL, $name = NULL);

	/**
	 * Set translate adapter
	 *
	 * @param Localization\ITranslator $translator
	 *
	 * @return $this
	 */
	public function setTranslator(Localization\ITranslator $translator = null);

	/**
	 * Set form renderer
	 *
	 * @param Forms\IFormRenderer $renderer
	 *
	 * @return $this
	 */
	public function setRenderer(Forms\IFormRenderer $renderer = null);

	/**
	 * Set form processor
	 *
	 * @param IFormProcessor $processor
	 * @return $this
	 */
	public function addProcessor(IFormProcessor $processor);

	/**
	 * Remove all or specific processors
	 *
	 * @param array $processors
	 * @return $this
	 */
	public function removeProcessors(array $processors = array());

	/**
	 * Get list of form processors
	 *
	 * @return array|IFormProcessor[]
	 */
	public function getProcessors();
}