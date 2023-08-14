<?php
declare (strict_types = 1);
namespace Lemuria;

/**
 * A Collector is a class that manages at least one EntitySet.
 */
interface Collector extends Identifiable
{
	/**
	 * This method will be called by the Catalog after loading is finished; the Collector can initialize its collections
	 * then.
	 */
	public function collectAll(): static;

	/**
	 * Get the relation that this Collector has to its collectibles.
	 */
	public function getRelation(): string;
}
