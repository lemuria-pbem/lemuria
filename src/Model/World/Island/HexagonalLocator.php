<?php
declare(strict_types = 1);
namespace Lemuria\Model\World\Island;

use Lemuria\Model\Coordinates;
use Lemuria\Model\Location;
use Lemuria\Model\World\MapCoordinates;

class HexagonalLocator extends OctagonalLocator
{
	/**
	 * Check if another island is a direct neighbour.
	 *
	 * @noinspection DuplicatedCode
	 */
	public function hasNeighbour(Island $island, Island $other): bool {
		if (parent::hasNeighbour($island, $other)) {
			return true;
		}

		$x1 = $island->Origin()->X();
		$y1 = $island->Origin()->Y();
		$w1 = $x1 + $island->Width();
		$h1 = $y1 + $island->Height();
		$x2 = $other->Origin()->X();
		$y2 = $other->Origin()->Y();
		$w2 = $x2 + $other->Width();
		$h2 = $y2 + $other->Height();

		if ($w2 === $x1 && $y2 === $h1) {
			return true; // Island is touching northwest.
		}
		if ($x2 === $w1 && $h2 === $y1) {
			return true; // Island is touching southeast.
		}

		return false;
	}

	protected function lookForNeighbour(Coordinates $coordinates, Island $island): ?Location {
		$neighbour = parent::lookForNeighbour($coordinates, $island);
		if (!$neighbour) {
			$neighbour = $island->get(new MapCoordinates($coordinates->X() - 1, $coordinates->Y() + 1)); // northwest
			if (!$neighbour) {
				$neighbour = $island->get(new MapCoordinates($coordinates->X() + 1, $coordinates->Y() - 1)); // southeast
			}
		}
		return $neighbour;
	}

	protected function addTouchingRegion(Coordinates $coordinates, Island $island): ?Location {
		$neighbour = parent::addTouchingRegion($coordinates, $island);
		if ($neighbour) {
			return $neighbour;
		}
		// Check if region is touching northwestward.
		if ($coordinates->X() === $island->Origin()->X() - 1 && $coordinates->Y() === $island->Outer()->Y()) {
			$neighbour = $island->get(new MapCoordinates($coordinates->X() + 1, $coordinates->Y() - 1));
			if ($neighbour) {
				$island->extendNorth();
				$island->extendWest();
				return $neighbour;
			}
		}
		// Check if region is touching southeastward.
		if ($coordinates->X() === $island->Outer()->X() && $coordinates->Y() === $island->Origin()->Y() - 1) {
			$neighbour = $island->get(new MapCoordinates($coordinates->X() - 1, $coordinates->Y() + 1));
			if ($neighbour) {
				$island->extendSouth();
				$island->extendEast();
				return $neighbour;
			}
		}
		return null;
	}
}
