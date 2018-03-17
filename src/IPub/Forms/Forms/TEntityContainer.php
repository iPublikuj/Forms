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

use Nette\Forms;

use IPub\Forms\Exceptions;

/**
 * Form trait for entity binding
 *
 * @package        iPublikuj:Forms!
 * @subpackage     Forms
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 *
 * @method Nette\Application\UI\Form getForm($need = TRUE)
 * @method setValues($values, $erase = FALSE)
 */
trait TEntityContainer
{
	/**
	 * @var mixed
	 */
	private $entity;

	/**
	 * @param array|\Traversable $values
	 * @param bool $erase
	 *
	 * @return void
	 */
	public function setDefaults($values, $erase = FALSE) : void
	{
		$form = $this->getForm(FALSE);

		if (!$form || !$form->isAnchored() || !$form->isSubmitted()) {
			if ($this->isEntity($values)) {
				$this->bindEntity($this, $values, $erase);

			} else {
				$this->setValues($values, $erase);
			}
		}
	}

	/**
	 * @param mixed $entity
	 *
	 * @return void
	 */
	public function setEntity($entity) : void
	{
		$this->entity = $entity;

		if (method_exists($entity, 'getId')) {
			$this->setId((string) $entity->getId());
		}
	}

	/**
	 * @return mixed
	 */
	public function getEntity()
	{
		return $this->entity;
	}

	/**
	 * @param Nette\ComponentModel\Component $formElement
	 * @param object $entity
	 * @param bool $erase
	 *
	 * @return void
	 *
	 * @throws Exceptions\InvalidArgumentException
	 */
	private function bindEntity($formElement, $entity, bool $erase) : void
	{
		$classMetadata = $this->getMetadata($entity);

		foreach (self::iterate($formElement) as $name => $component) {
			if ($component instanceof Forms\IControl) {
				if (($classMetadata->hasField($name) || $classMetadata->hasAssociation($name)) && $value = $classMetadata->getFieldValue($entity, $name)) {
					if (is_object($value) && $this->isEntity($value)) {
						$UoW = $this->entityManager->getUnitOfWork();
						$value = $UoW->getSingleIdentifierValue($value);

						$component->setValue($value);

					} else {
						$component->setValue($value);
					}

				} else {
					$methodName = 'get' . ucfirst($name);

					if (method_exists($entity, $methodName) && $value = call_user_func([$entity, $methodName])) {
						if (is_object($value) && $this->isEntity($value)) {
							$UoW = $this->entityManager->getUnitOfWork();
							$value = $UoW->getSingleIdentifierValue($value);

							$component->setValue($value);

						} else {
							$component->setValue($value);
						}

					} else {
						if ($erase) {
							$component->setValue(NULL);
						}
					}
				}

			} elseif ($component instanceof Forms\Container) {
				if (($classMetadata->hasField($name) || $classMetadata->hasAssociation($name)) && $value = $classMetadata->getFieldValue($entity, $name)) {
					if (is_object($value) && $this->isEntity($value)) {
						$this->bindEntity($component, $value, $erase);

					} else {
						$component->setValues($value, $erase);
					}

				} else {
					$methodName = 'get' . ucfirst($name);

					if (method_exists($entity, $methodName) && $value = call_user_func([$entity, $methodName])) {
						if (is_object($value) && $this->isEntity($value)) {
							$this->bindEntity($component, $value, $erase);

						} else {
							$component->setValues($value, $erase);
						}

					} else {
						if ($erase) {
							$component->setValues([], $erase);
						}
					}
				}
			}
		}
	}

	/**
	 * @param object $entity
	 *
	 * @return ORM\Mapping\ClassMetadata
	 *
	 * @throws Exceptions\InvalidArgumentException
	 */
	private function getMetadata($entity) : ORM\Mapping\ClassMetadata
	{
		if (!$this->isEntity($entity)) {
			throw new Exceptions\InvalidArgumentException(sprintf('Expected object, "%s" given.', gettype($entity)));
		}

		return $this->entityManager->getClassMetadata(get_class($entity));
	}

	/**
	 * @param mixed $entity
	 *
	 * @return bool
	 */
	private function isEntity($entity) : bool
	{
		return is_object($entity) && $this->entityManager->getMetadataFactory()->hasMetadataFor(get_class($entity));
	}

	/**
	 * @param Forms\IControl|Forms\Container $formElement
	 *
	 * @return array|\ArrayIterator
	 *
	 * @throws Exceptions\InvalidArgumentException
	 */
	private static function iterate($formElement)
	{
		if ($formElement instanceof Forms\Container) {
			return $formElement->getComponents();

		} elseif ($formElement instanceof Forms\IControl) {
			return [$formElement];

		} else {
			throw new Exceptions\InvalidArgumentException(sprintf('Expected Nette\Forms\Container or Nette\Forms\IControl, but "%s" given', get_class($formElement)));
		}
	}
}
