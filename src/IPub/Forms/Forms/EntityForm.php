<?php
/**
 * EntityForm.php
 *
 * @copyright      More in license.md
 * @license        https://www.ipublikuj.eu
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 * @package        iPublikuj:Forms!
 * @subpackage     Forms
 * @since          1.0.0
 *
 * @date           26.05.13
 */

declare(strict_types = 1);

namespace IPub\Forms\Forms;

use Doctrine\ORM;

use Nette\Application;

/**
 * Form with entity support
 *
 * @package        iPublikuj:Forms!
 * @subpackage     Forms
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
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
	 */
	public function injectEntityManager(ORM\EntityManager $entityManager) : void
	{
		$this->entityManager = $entityManager;
	}

	/**
	 * Adds naming container to the form
	 *
	 * @param string $name
	 *
	 * @return EntityContainer
	 */
	public function addContainer($name) : EntityContainer
	{
		$control = new EntityContainer($this->entityManager);
		$control->setCurrentGroup($this->currentGroup);

		return $this[$name] = $control;
	}

	/**
	 * @return ORM\EntityManager
	 */
	protected function getEntityManager() : ORM\EntityManager
	{
		return $this->entityManager;
	}
}
