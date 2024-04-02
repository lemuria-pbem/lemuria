<?php
declare (strict_types = 1);
namespace Lemuria\Sorting;

use Lemuria\Entity;
use Lemuria\EntityOrder;
use Lemuria\EntitySet;

/**
 * The sorting which orders by the name of entities.
 */
class ByName implements EntityOrder
{
	/**
	 * Sort entities and return the entity IDs in sorted order.
	 *
	 * @return array<int>
	 */
	public function sort(EntitySet $set): array {
		$ids = [];
		foreach ($set as $entity) {
			/** @var Entity $entity */
			$ids[$entity->Id()->Id()] = $entity->Name();
		}
		asort($ids, SORT_LOCALE_STRING);
		return array_keys($ids);
	}
}
