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
	 * @var array<string, Collector>
	 */
	private array $collectors = [];

	public function addCollector(Collector $collector): Collectible {
		$this->collectors[$collector->getRelation()] = $collector;
		/** @var Collectible $this */
		return $this;
	}

	public function removeCollector(Collector $collector): Collectible {
		$relation = $collector->getRelation();
		if (isset($this->collectors[$relation])) {
			$oldCollector = $this->collectors[$relation];
			if ($oldCollector->Id()->Id() === $collector->Id()->Id()) {
				unset($this->collectors[$relation]);
			}
		}
		/** @var Collectible $this */
		return $this;
	}

	protected function hasCollector(string $relation): bool {
		return isset($this->collectors[$relation]);
	}

	protected function getCollector(string $relation): Collector {
		if (!isset($this->collectors[$relation])) {
			throw new LemuriaException('This Entity has no ' . $relation . ' Collector.');
		}
		return $this->collectors[$relation];
	}

	/**
	 * @param array<string> $availableCollectors
	 */
	public function findCollector(array $availableCollectors): Collector {
		foreach ($availableCollectors as $collector) {
			$collector = getClass($collector);
			if ($this->hasCollector($collector)) {
				return $this->getCollector($collector);
			}
		}

		$set = [];
		foreach ($availableCollectors as $collector) {
			$set[] = getClass($collector);
		}
		throw new LemuriaException('This Entity has no Collector from the set [' . implode(', ', $set) . '].');
	}
}
