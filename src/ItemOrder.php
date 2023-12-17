<?php
declare (strict_types = 1);
namespace Lemuria;

/**
 * An ItemSet can be sorted by ItemOrder objects.
 */
interface ItemOrder
{
	/**
	 * Sort items and return the singletons in sorted order.
	 *
	 * @return array<string>
	 */
	public function sort(ItemSet $set): array;
}
