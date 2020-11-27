<?php
declare (strict_types = 1);
namespace Lemuria\Sorting\Location;

use Lemuria\EntityOrder;
use Lemuria\EntitySet;
use Lemuria\Lemuria;
use Lemuria\Model\Location;

/**
 * An ordering for locations using sorting by coordinates.
 */
class North2South implements EntityOrder
{
	/**
	 * Sort entities and return the entity IDs in sorted order.
	 *
	 * @return int[]
	 */
	public function sort(EntitySet $set): array {
		$ids         = [];
		$coordinates = [];
		foreach ($set as $location /** @var Location $location */) {
			$coord = Lemuria::World()->getCoordinates($location);
			$x     = $coord->X();
			$y     = $coord->Y();
			if (!isset($coordinates[$y])) {
				$coordinates[$y] = [];
			}
			$coordinates[$y][$x] = $location->Id()->Id();
		}
		ksort($coordinates);
		foreach (array_reverse($coordinates, true) as $row) {
			ksort($row);
			foreach ($row as $id) {
				$ids[] = $id;
			}
		}
		return $ids;
	}
}
