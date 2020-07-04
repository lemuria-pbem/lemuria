<?php
declare (strict_types = 1);
namespace Lemuria;

/**
 * Common implementation of an Identifier.
 */
trait IdentifiableTrait
{
	/**
	 * @var Id
	 */
	private $id;

	/**
	 * Get the ID.
	 *
	 * @return Id
	 */
	public function Id(): Id {
		return $this->id;
	}

	/**
	 * Set the ID.
	 *
	 * @param Id $id
	 * @return Entity
	 */
	public function setId(Id $id): Identifiable {
		if ($this->id) {
			/* @var Identifiable $this */
			Lemuria::Catalog()->remove($this);
		}
		$this->id = $id;
		/* @var Identifiable $this */
		Lemuria::Catalog()->register($this);
		/* @var Identifiable $this */
		return $this;
	}
}
