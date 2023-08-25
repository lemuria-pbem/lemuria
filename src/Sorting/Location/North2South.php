<?php
declare (strict_types = 1);
namespace Lemuria\Sorting\Location;

use Lemuria\EntityOrder;
use Lemuria\EntitySet;
use Lemuria\Lemuria;
use Lemuria\Model\Location;
use Lemuria\Model\World\Atlas;

/**
 * An ordering for locations using sorting by coordinates.
 */
class North2South implements EntityOrder
{
	/**
	 * Sort entities and return the entity IDs in sorted order.
	 *
	 * @param Atlas $set
	 * @return array<int>
	 */
	public function sort(EntitySet $set): array {
		$ids         = [];
		$coordinates = $this->getSortedLocations($set);
		foreach ($coordinates as $row) {
			ksort($row);
			foreach ($row as $id) {
				$ids[] = $id->Id()->Id();
			}
		}
		return $ids;
	}

	/**
	 * @return array<int, array<int, Location>>
	 */
	protected function getSortedLocations(EntitySet $set): array {
		$coordinates = [];
		foreach ($set as $location) {
			$coord = Lemuria::World()->getCoordinates($location);
			$x     = $coord->X();
			$y     = $coord->Y();
			if (!isset($coordinates[$y])) {
				$coordinates[$y] = [];
			}
			$coordinates[$y][$x] = $location;
		}
		ksort($coordinates);
		return array_reverse($coordinates, true);
	}
}
