<?php
/**
 * EntityContainer.php
 *
 * @copyright      More in license.md
 * @license        https://www.ipublikuj.eu
 * @author         Adam Kadlec https://www.ipublikuj.eu
 * @package        iPublikuj:Forms!
 * @subpackage     Forms
 * @since          1.0.0
 *
 * @date           10.01.16
 */

declare(strict_types = 1);

namespace IPub\Forms\Forms;

use Doctrine\ORM;

use Nette\Forms;

/**
 * Form container with entity support
 *
 * @package        iPublikuj:Forms!
 * @subpackage     Forms
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
class EntityContainer extends Forms\Container
{
	/**
	 * Implement entity handling in form
	 */
	use TEntityContainer;

	/**
	 * @var ORM\EntityManager
	 */
	private $entityManager;

	/**
	 * @param ORM\EntityManager $entityManager
	 */
	public function __construct(ORM\EntityManager $entityManager)
	{
		parent::__construct();

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
		$control = new self($this->entityManager);
		$control->setCurrentGroup($this->currentGroup);

		return $this[$name] = $control;
	}
}
