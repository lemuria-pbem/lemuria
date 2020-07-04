<?php
declare (strict_types = 1);
namespace Lemuria;

/**
 * A Collector is a class that manages at least one EntitySet.
 */
interface Collector extends Identifiable
{
	const CONSTRUCTION = 'Construction';

	const PARTY = 'Party';

	const REGION = 'Region';

	const VESSEL = 'Vessel';

	/**
	 * This method will be called by the Catalog after loading is finished; the Collector can initialize its collections
	 * then.
	 *
	 * @return Collector
	 */
	public function collectAll(): Collector;

	/**
	 * Get the relation that this Collector has to its collectibles.
	 *
	 * @return string
	 */
	public function getRelation(): string;
}
