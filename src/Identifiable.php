<?php
declare (strict_types = 1);
namespace Lemuria;

use JetBrains\PhpStorm\ExpectedValues;

use Lemuria\Model\Catalog;

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
	#[ExpectedValues(valuesFromClass: Catalog::class)] public function Catalog(): int;

	/**
	 * Set the ID.
	 */
	public function setId(Id $id): Identifiable;
}
