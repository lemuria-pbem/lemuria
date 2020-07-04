<?php
declare (strict_types = 1);
namespace Lemuria;

/**
 * Common implementation of a Collector.
 */
trait CollectorTrait
{
	/**
	 * This method will be called by the Catalog after loading is finished; the Collector can initialize its collections
	 * then.
	 *
	 * @return Collector
	 */
	public function collectAll(): Collector {
		/* @var Collector $this */
		return $this;
	}

	/**
	 * Get the relation that this Collector has to its collectibles.
	 *
	 * @return string
	 */
	public function getRelation(): string {
		return getClass($this);
	}
}
