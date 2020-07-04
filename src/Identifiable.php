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
	 *
	 * @return Id
	 */
	public function Id(): Id;

	/**
	 * Get the catalog namespace.
	 *
	 * @return int
	 */
	public function Catalog(): int;

	/**
	 * Set the ID.
	 *
	 * @param Id $id
	 * @return Entity
	 */
	public function setId(Id $id): Identifiable;
}
