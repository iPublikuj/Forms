<?php
/**
 * EntityForm.php
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

use Doctrine\ORM;

use Nette;
use Nette\Application;
use Nette\ComponentModel;

class EntityForm extends Application\UI\Form
{
	/**
	 * Implement entity handling in form
	 */
	use TEntityContainer;

	/**
	 * Implement form methods
	 */
	use TForm;

	/**
	 * @var ORM\EntityManager
	 */
	private $entityManager;

	/**
	 * @param ORM\EntityManager $entityManager
	 * @param ComponentModel\IContainer|NULL $parent
	 * @param string|NULL $name
	 */
	public function __construct(
		ORM\EntityManager $entityManager,
		ComponentModel\IContainer $parent = NULL,
		string $name = NULL
	) {
		parent::__construct($parent, $name);

		$this->entityManager = $entityManager;
	}

	/**
	 * Adds naming container to the form
	 *
	 * @param string $name
	 *
	 * @return Container
	 */
	public function addContainer($name) : Container
	{
		$control = new Container($this->entityManager);
		$control->setCurrentGroup($this->currentGroup);

		return $this[$name] = $control;
	}
}
