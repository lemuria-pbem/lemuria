<?php
declare(strict_types = 1);
namespace Lemuria\Sorting;

use Lemuria\ItemOrder;
use Lemuria\ItemSet;

class BySingleton implements ItemOrder
{
	/**
	 * Sort items and return the singletons in sorted order.
	 *
	 * @return array<string>
	 */
	public function sort(ItemSet $set): array {
		$sorted = [];
		foreach ($set as $item) {
			$sorted[(string)$item->getObject()] = true;
		}
		ksort($sorted, SORT_STRING);
		return array_keys($sorted);
	}
}
