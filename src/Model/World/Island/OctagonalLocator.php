<?php
declare(strict_types = 1);
namespace Lemuria\Model\World\Island;

use Lemuria\Exception\LemuriaException;
use Lemuria\Model\Coordinates;
use Lemuria\Model\Location;
use Lemuria\Model\World\MapCoordinates;

class OctagonalLocator implements Locator
{
	public function findNeighbour(Location $location, Coordinates $coordinates, Island $island): ?Location {
		if ($island->isMapped($coordinates)) {
			// Region is within island area.
			if ($island->get($coordinates) === $location) {
				throw new LemuriaException('There is already a location at these coordinates.');
			}
			// Look for a neighbour.
			$neighbour = $this->lookForNeighbour($coordinates, $island);
		} else {
			// Check if region is touching the island area.
			$neighbour = $this->addTouchingRegion($coordinates, $island);
		}
		return $neighbour;
	}

	/**
	 * Check if another island has common coordinates.
	 */
	public function hasIntersection(Island $island, Island $other): bool {
		$x1 = $island->Origin()->X();
		$y1 = $island->Origin()->Y();
		$w1 = $x1 + $island->Width() - 1;
		$h1 = $y1 + $island->Height() - 1;
		$x2 = $other->Origin()->X();
		$y2 = $other->Origin()->Y();
		$w2 = $x2 + $other->Width() - 1;
		$h2 = $y2 + $other->Height() - 1;

		if ($h2 >= $y1 && $h2 <= $h1) {
			if ($x2 >= $x1 && $x2 <= $w1) {
				return true; // Island's NW corner intersects.
			}
			if ($w2 >= $x1 && $w2 <= $w1) {
				return true; // Island's NE corner intersects.
			}
		} elseif ($y2 >= $y1 && $y2 <= $h1) {
			if ($x2 >= $x1 && $x2 <= $w1) {
				return true; // Island's SW corner intersects.
			}
			if ($w2 >= $x1 && $w2 <= $w1) {
				return true; // Island's SE corner intersects.
			}
		}
		return false;
	}

	/**
	 * Check if another island is a direct neighbour.
	 *
	 * @noinspection DuplicatedCode
	 */
	public function hasNeighbour(Island $island, Island $other): bool {
		$x1 = $island->Origin()->X();
		$y1 = $island->Origin()->Y();
		$w1 = $x1 + $island->Width();
		$h1 = $y1 + $island->Height();
		$x2 = $other->Origin()->X();
		$y2 = $other->Origin()->Y();
		$w2 = $x2 + $other->Width();
		$h2 = $y2 + $other->Height();

		if ($x1 < $w2 && $x2 < $w1) {
			if ($y2 === $h1) {
				return true; // Island is touching north.
			}
			if ($y1 === $h2) {
				return true; // Island is touching south.
			}
		} elseif ($y1 < $h2 && $y2 < $h1) {
			if ($x2 === $w1) {
				return true; // Island is touching east.
			}
			if ($x1 === $w2) {
				return true; // Island is touching west.
			}
		}
		return false;
	}

	protected function lookForNeighbour(Coordinates $coordinates, Island $island): ?Location {
		$neighbour = $island->get(new MapCoordinates($coordinates->X(), $coordinates->Y() + 1)); // north
		if (!$neighbour) {
			$neighbour = $island->get(new MapCoordinates($coordinates->X() + 1, $coordinates->Y())); // east
			if (!$neighbour) {
				$neighbour = $island->get(new MapCoordinates($coordinates->X(), $coordinates->Y() - 1)); // south
				if (!$neighbour) {
					$neighbour = $island->get(new MapCoordinates($coordinates->X() - 1, $coordinates->Y())); // west
				}
			}
		}
		return $neighbour;
	}

	protected function addTouchingRegion(Coordinates $coordinates, Island $island): ?Location {
		$neighbour = null;
		if ($coordinates->X() >= $island->Origin()->X() && $coordinates->X() < $island->Outer()->X()) {
			if ($coordinates->Y() === $island->Outer()->Y()) {
				// Check if region is touching northward.
				$neighbour = $island->get(new MapCoordinates($coordinates->X(), $island->Outer()->Y() - 1));
				if ($neighbour) {
					$island->extendNorth();
				}
			} elseif ($coordinates->Y() === $island->Origin()->Y() - 1) {
				// Check if region is touching southward.
				$neighbour = $island->get(new MapCoordinates($coordinates->X(), $island->Origin()->Y()));
				if ($neighbour) {
					$island->extendSouth();
				}
			}
		} elseif ($coordinates->Y() >= $island->Origin()->Y() && $coordinates->Y() < $island->Outer()->Y()) {
			if ($coordinates->X() === $island->Outer()->X()) {
				// Check if region is touching eastward.
				$neighbour = $island->get(new MapCoordinates($island->Outer()->X() - 1, $coordinates->Y()));
				if ($neighbour) {
					$island->extendEast();
				}
			} elseif ($coordinates->X() === $island->Origin()->X() - 1) {
				// Check if region is touching westward.
				$neighbour = $island->get(new MapCoordinates($island->Origin()->X(), $coordinates->Y()));
				if ($neighbour) {
					$island->extendWest();
				}
			}
		}
		return $neighbour;
	}
}
