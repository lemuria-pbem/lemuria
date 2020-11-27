<?php
declare (strict_types = 1);
namespace Lemuria;

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
	 * Get the catalog namespace.
	 */
	public function Catalog(): int;

	/**
	 * Set the ID.
	 */
	public function setId(Id $id): Identifiable;
}
