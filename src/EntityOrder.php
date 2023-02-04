<?php
declare (strict_types = 1);
namespace Lemuria;

/**
 * An EntitySet can be sorted by EntityOrder objects.
 */
interface EntityOrder
{
	/**
	 * Sort entities and return the entity IDs in sorted order.
	 *
	 * @return array<int>
	 */
	public function sort(EntitySet $set): array;
}
