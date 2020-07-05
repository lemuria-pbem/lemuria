<?php
declare (strict_types = 1);
namespace Lemuria;

/**
 * A Collectible is an Entity that knows its Collector.
 */
interface Collectible
{
	/**
	 * Set the Collector.
	 *
	 * @param Collector $collector
	 * @return Collectible
	 */
	public function addCollector(Collector $collector): Collectible;

	/**
	 * Remove the Collector.
	 *
	 * @param Collector $collector
	 * @return Collectible
	 */
	public function removeCollector(Collector $collector): Collectible;
}
