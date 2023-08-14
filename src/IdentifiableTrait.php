<?php
declare (strict_types = 1);
namespace Lemuria;

/**
 * Common implementation of an Identifier.
 */
trait IdentifiableTrait
{
	private ?Id $id = null;

	public function Id(): Id {
		return $this->id;
	}

	public function setId(Id $id): static {
		if ($this->id) {
			Lemuria::Catalog()->remove($this);
		}
		$this->id = $id;
		Lemuria::Catalog()->register($this);
		return $this;
	}
}
