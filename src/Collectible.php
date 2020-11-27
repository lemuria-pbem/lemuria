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
	 */
	public function addCollector(Collector $collector): Collectible;

	/**
	 * Remove the Collector.
	 */
	public function removeCollector(Collector $collector): Collectible;
}
