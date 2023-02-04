<?php
declare (strict_types = 1);
namespace Lemuria\Model\World\Island;

use Lemuria\Exception\LemuriaException;
use Lemuria\Id;
use Lemuria\Model\Coordinates;
use Lemuria\Model\Location;
use Lemuria\Model\World\MapCoordinates;

/**
 * An island consists of neighbouring land regions and are surrounded by ocean or chaos.
 *
 * Locations that are direct north-south or east-west neighbours are on the same island. If they are just touching
 * diagonally (separated by a strait), they belong to separate islands.
 */
class Island
{
	private static int $nextId = 1;

	private readonly Id $id;

	private Coordinates $outer;

	/**
	 * @var array<int, array<int, Location>>
	 */
	private array $map;

	private int $width = 1;

	private int $height = 1;

	public function __construct(private Coordinates $origin, Location $location, private readonly Locator $locator) {
		$this->id  = new Id(self::$nextId++);
		$this->map = [[$location]];
		$this->updateOuter();
	}

	public function Id(): Id {
		return $this->id;
	}

	public function Origin(): Coordinates {
		return $this->origin;
	}

	public function Width(): int {
		return $this->width;
	}

	public function Height(): int {
		return $this->height;
	}

	public function Outer(): Coordinates {
		return $this->outer;
	}

	public function Size(): int {
		$size = 0;
		for ($y = 0; $y < $this->height; $y++) {
			for ($x = 0; $x < $this->width; $x++) {
				if ($this->map[$y][$x]) {
					$size++;
				}
			}
		}
		return $size;
	}

	public function isMapped(Coordinates $coordinates): bool {
		if ($coordinates->X() >= $this->origin->X() && $coordinates->X() < $this->outer->X()) {
			if ($coordinates->Y() >= $this->origin->Y() && $coordinates->Y() < $this->outer->Y()) {
				return true;
			}
		}
		return false;
	}

	public function contains(Location $location): bool {
		for ($y = 0; $y < $this->height; $y++) {
			for ($x = 0; $x < $this->width; $x++) {
				if ($this->map[$y][$x] === $location) {
					return true;
				}
			}
		}
		return false;
	}

	public function get(Coordinates $coordinates): ?Location {
		if ($this->isMapped($coordinates)) {
			$x = $coordinates->X() - $this->origin->X();
			$y = $coordinates->Y() - $this->origin->Y();
			return $this->map[$y][$x];
		}
		return null;
	}

	public function add(Coordinates $coordinates, Location $location): Island {
		if ($this->isMapped($coordinates)) {
			$existing = $this->get($coordinates);
			if ($existing === $location) {
				return $this; // Location already added.
			}
		}

		$neighbour = $this->locator->findNeighbour($location, $coordinates, $this);
		if (!$neighbour) {
			throw new LemuriaException('There is no landmass next to the region.');
		}
		$x                 = $coordinates->X() - $this->origin->X();
		$y                 = $coordinates->Y() - $this->origin->Y();
		$this->map[$y][$x] = $location;
		return $this;
	}

	/**
	 * Extend northward and return new height.
	 */
	public function extendNorth(): int {
		$this->map[] = array_fill(0, $this->width, null);
		$this->height++;
		$this->updateOuter();
		return $this->height;
	}

	/**
	 * Extend eastward and return new width.
	 */
	public function extendEast(): int {
		for ($y = 0; $y < $this->height; $y++) {
			$this->map[$y][] = null;
		}
		$this->width++;
		$this->updateOuter();
		return $this->width;
	}

	/**
	 * Extend southward and return new height.
	 */
	public function extendSouth(): int {
		array_unshift($this->map, array_fill(0, $this->width, null));
		$this->origin = new MapCoordinates($this->origin->X(), $this->origin->Y() - 1);
		$this->height++;
		$this->updateOuter();
		return $this->height;
	}

	/**
	 * Extend westward and return new width.
	 */
	public function extendWest(): int {
		for ($y = 0; $y < $this->height; $y++) {
			array_unshift($this->map[$y], null);
		}
		$this->origin = new MapCoordinates($this->origin->X() - 1, $this->origin->Y());
		$this->width++;
		$this->updateOuter();
		return $this->width;
	}

	/**
	 * Check if another island has common coordinates.
	 */
	public function hasIntersection(Island $island): bool {
		return $this->locator->hasIntersection($this, $island);
	}

	/**
	 * Check if another island is a direct neighbour.
	 */
	public function hasNeighbour(Island $island): bool {
		return $this->locator->hasNeighbour($this, $island);
	}

	/**
	 * Merge another island.
	 *
	 * @throws LemuriaException
	 */
	public function merge(Island $island): Island {
		$locations = [];
		$w       = $island->Origin()->X() + $island->Width();
		$h       = $island->Origin()->Y() + $island->Height();
		for ($y = $island->Origin()->Y(); $y < $h; $y++) {
			for ($x = $island->Origin()->X(); $x < $w; $x++) {
				$coordinates = new MapCoordinates($x, $y);
				$location      = $island->get($coordinates);
				if ($location) {
					$locations[] = [$coordinates, $location];
				}
			}
		}

		$merged = clone $this;
		do {
			$count = count($locations);
			foreach (array_keys($locations) as $i) {
				$coordinates = $locations[$i][0];
				$location      = $locations[$i][1];
				try {
					$merged->add($coordinates, $location);
					unset ($locations[$i]);
				} catch (LemuriaException) {
				}
			}
		} while ($count > 0 && count($locations) < $count);

		if ($count > 0) {
			throw new LemuriaException('Island cannot be merged completely.');
		}
		$this->origin = $merged->origin;
		$this->width  = $merged->width;
		$this->height = $merged->height;
		$this->map    = $merged->map;
		$this->updateOuter();
		return $this;
	}

	public function getLocations(): array {
		$locations = [];
		for ($y = 0; $y < $this->height; $y++) {
			for ($x = 0; $x < $this->width; $x++) {
				$location = $this->map[$y][$x];
				if ($location) {
					$locations[] = $location;
				}
			}
		}
		return $locations;
	}

	protected function updateOuter(): void {
		$this->outer = new MapCoordinates($this->outerX(), $this->outerY());
	}

	protected function outerX(): int {
		return $this->origin->X() + $this->width;
	}

	protected function outerY(): int {
		return $this->origin->Y() + $this->height;
	}
}
