<?php
declare (strict_types = 1);
namespace Lemuria;

use JetBrains\PhpStorm\ExpectedValues;
use JetBrains\PhpStorm\Pure;

use Lemuria\Model\Catalog;

/**
 * An identifiable class must implement the ID property and will be registered in the catalog.
 */
interface Identifiable
{
	/**
	 * Get the ID.
	 */
	#[Pure] public function Id(): Id;

	/**
	 * Get the catalog namespace.
	 */
	#[ExpectedValues(valuesFromClass: Catalog::class)]
	#[Pure]
	public function Catalog(): int;

	/**
	 * Set the ID.
	 */
	public function setId(Id $id): Identifiable;
}
