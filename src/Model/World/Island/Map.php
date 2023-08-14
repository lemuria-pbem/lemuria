<?php
declare (strict_types = 1);
namespace Lemuria\Model\World\Island;

use Lemuria\CountableTrait;
use Lemuria\Exception\LemuriaException;
use Lemuria\IteratorTrait;
use Lemuria\Model\Coordinates;
use Lemuria\Model\Location;
use Lemuria\Model\World;
use Lemuria\Model\World\HexagonalMap;

/**
 * A map represents the regions of the world as islands.
 */
class Map implements \Countable, \Iterator
{
	use CountableTrait;
	use IteratorTrait;

	/**
	 * @var array<Island>
	 */
	protected array $islands = [];

	/**
	 * @var array<int, array<int>>
	 */
	protected array $longitude = [];

	/**
	 * @var array<int, array<int>>
	 */
	protected array $latitude = [];

	protected Locator $locator;

	public function __construct(World $world) {
		if ($world instanceof HexagonalMap) {
			$this->locator = new HexagonalLocator();
		} else {
			$this->locator = new OctagonalLocator();
		}
	}

	public function current(): ?Island {
		return $this->islands[$this->index] ?? null;
	}

	/**
	 * Find island that contains a region.
	 */
	public function search(Location $location): ?Island {
		foreach ($this->islands as $island) {
			if ($island->contains($location)) {
				return $island;
			}
		}
		return null;
	}

	/**
	 * @throws LemuriaException
	 */
	public function add(Coordinates $coordinates, Location $location): Island {
		foreach ($this->findIslands($coordinates) as $island) {
			try {
				$island->add($coordinates, $location);
				return $this->merge()->mergeReverse()->search($location);
			} catch (LemuriaException) {
			}
		}

		$island          = new Island($coordinates, $location, $this->locator);
		$index           = ++$this->count;
		$this->islands[] = $island;
		if (!isset($this->longitude[$coordinates->X()])) {
			$this->longitude[$coordinates->X()] = [];
		}
		if (!isset($this->latitude[$coordinates->Y()])) {
			$this->latitude[$coordinates->Y()] = [];
		}
		$this->longitude[$coordinates->X()][] = $index;
		$this->latitude[$coordinates->Y()][]  = $index;
		return $this->merge()->search($location);
	}

	/**
	 * @return array<Island>
	 */
	protected function findIslands(Coordinates $coordinates): array {
		$longitude = $this->longitude[$coordinates->X()] ?? [];
		$latitude  = $this->latitude[$coordinates->Y()] ?? [];
		$islands   = [];
		foreach ($longitude as $w) {
			foreach ($latitude as $h) {
				if ($h === $w) {
					$islands[] = $this->islands[$h];
				}
			}
		}
		return $islands;
	}

	protected function merge(): static {
		do {
			$merged = null;
			$last   = $this->count - 1;
			$f      = 0;
			while ($f < $last) {
				$first = $this->islands[$f];
				$s     = $f + 1;
				while ($s <= $last) {
					$second = $this->islands[$s];
					if ($first->hasIntersection($second) || $first->hasNeighbour($second)) {
						try {
							$merged = $first->merge($second);
							unset($this->islands[$s]);
							$this->count--;
							$this->islands = array_values($this->islands);
							$this->updatePointers($f, $s);
							break;
						} catch (LemuriaException) {
						}
					}
					$s++;
				}
				if ($merged) {
					break;
				}
				$f++;
			}
		} while ($merged);
		return $this;
	}

	protected function mergeReverse(): static {
		$this->islands = array_reverse($this->islands);
		return $this->merge();
	}

	protected function updatePointers(int $first, int $second): void {
		foreach (array_keys($this->longitude) as $w) {
			$pointers = $this->longitude[$w];
			$indices  = [];
			foreach ($pointers as $index) {
				if ($index > $second) {
					$indices[$index - 1] = true;
				} elseif ($index === $second) {
					$indices[$first] = true;
				} else {
					$indices[$index] = true;
				}
			}
			$this->longitude[$w] = array_keys($indices);
		}

		foreach (array_keys($this->latitude) as $h) {
			$pointers = $this->latitude[$h];
			$indices  = [];
			foreach ($pointers as $index) {
				if ($index > $second) {
					$indices[$index - 1] = true;
				} elseif ($index === $second) {
					$indices[$first] = true;
				} else {
					$indices[$index] = true;
				}
			}
			$this->latitude[$h] = array_keys($indices);
		}
	}
}
