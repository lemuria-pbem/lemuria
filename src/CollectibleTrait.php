<?php
declare (strict_types = 1);
namespace Lemuria;

use Lemuria\Exception\LemuriaException;

/**
 * Common implementation of a Collectible.
 */
trait CollectibleTrait
{
	/**
	 * @var array(string=>Collector)
	 */
	private $collectors = [];

	/**
	 * Set a Collector.
	 *
	 * @param Collector $collector
	 * @return Collectible
	 */
	public function addCollector(Collector $collector): Collectible {
		$this->collectors[$collector->getRelation()] = $collector;
		/* @var Collectible $this */
		return $this;
	}

	/**
	 * Remove the Collector.
	 *
	 * @param Collector $collector
	 * @return Collectible
	 */
	public function removeCollector(Collector $collector): Collectible {
		$relation = $collector->getRelation();
		if (isset($this->collectors[$relation])) {
			/* @var Collector $oldCollector */
			$oldCollector = $this->collectors[$relation];
			if ($oldCollector->Id()->Id() === $collector->Id()->Id()) {
				unset($this->collectors[$relation]);
			}
		}
		/* @var Collectible $this */
		return $this;
	}

	/**
	 * Check a Collector.
	 *
	 * @param string $relation
	 * @return bool
	 */
	protected function hasCollector(string $relation): bool {
		return isset($this->collectors[$relation]);
	}

	/**
	 * Get a Collector.
	 *
	 * @param string $relation
	 * @return Collector
	 */
	protected function getCollector(string $relation): Collector {
		if (!isset($this->collectors[$relation])) {
			throw new LemuriaException('This Entity has no ' . $relation . ' Collector.');
		}
		return $this->collectors[$relation];
	}
}
