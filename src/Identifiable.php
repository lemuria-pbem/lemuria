<?php
declare (strict_types = 1);
namespace Lemuria;

use Lemuria\Model\Domain;

/**
 * An identifiable class must implement the ID property and will be registered in the catalog.
 */
interface Identifiable
{
	/**
	 * Get the ID.
	 */
	public function Id(): Id;

	/**
	 * Get the catalog domain.
	 */
	public function Catalog(): Domain;

	/**
	 * Set the ID.
	 */
	public function setId(Id $id): static;
}
