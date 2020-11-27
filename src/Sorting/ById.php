<?php
declare (strict_types = 1);
namespace Lemuria\Sorting;

use Lemuria\EntityOrder;
use Lemuria\EntitySet;

/**
 * The default sorting which simply orders by the ID of entities.
 */
class ById implements EntityOrder
{
	/**
	 * Sort entities and return the entity IDs in sorted order.
	 *
	 * @param EntitySet $set
	 * @return int[]
	 */
	public function sort(EntitySet $set): array {
		$ids = [];
		foreach ($set as $entity) {
			$ids[] = $entity->Id()->Id();
		}
		sort($ids);
		return $ids;
	}
}
