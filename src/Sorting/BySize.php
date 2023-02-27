<?php
declare (strict_types = 1);
namespace Lemuria\Sorting;

use Lemuria\EntityOrder;
use Lemuria\EntitySet;
use Lemuria\Exception\LemuriaException;
use Lemuria\Model\Sized;

/**
 * A sorting for entities that are sized.
 */
class BySize implements EntityOrder
{
	/**
	 * Sort entities and return the entity IDs in sorted order.
	 *
	 * @return array<int>
	 */
	public function sort(EntitySet $set): array {
		$sizes = [];
		foreach ($set as $entity) {
			$id = $entity->Id()->Id();
			if (!$entity instanceof Sized) {
				throw new LemuriaException('Entity is not sized.');
			}
			$size           = $entity->Size();
			$sizes[$size][] = $id;
		}
		ksort($sizes);

		$ids = [];
		foreach ($sizes as $entities) {
			sort($entities);
			array_push($ids, ...$entities);
		}
		return $ids;
	}
}
