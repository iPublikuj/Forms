<?php
/**
 * TEntityContainer.php
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:Forms!
 * @subpackage     Forms
 * @since          1.0.0
 *
 * @date           10.01.16
 */

declare(strict_types = 1);

namespace IPub\Forms\Forms;

use Doctrine\ORM;

use Nette;
use Nette\Forms;

class Container extends Forms\Container
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
	 * @return Container
	 */
	public function addContainer($name) : Container
	{
		$control = new self($this->entityManager);
		$control->setCurrentGroup($this->currentGroup);

		return $this[$name] = $control;
	}
}
