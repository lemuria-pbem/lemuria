<?php
declare (strict_types = 1);
namespace Lemuria;

use JetBrains\PhpStorm\Pure;

/**
 * Common implementation of a Collector.
 */
trait CollectorTrait
{
	/**
	 * This method will be called by the Catalog after loading is finished; the Collector can initialize its collections
	 * then.
	 */
	public function collectAll(): Collector {
		/* @var Collector $this */
		return $this;
	}

	/**
	 * Get the relation that this Collector has to its collectibles.
	 */
	#[Pure] public function getRelation(): string {
		return getClass($this);
	}
}
