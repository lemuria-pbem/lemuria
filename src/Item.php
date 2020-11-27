<?php
declare (strict_types = 1);
namespace Lemuria;

use JetBrains\PhpStorm\Pure;

use Lemuria\Exception\ItemException;

/**
 * An item is a quantity of one object.
 *
 * Since the object is a Singleton, two items of the same object can easily be added or subtracted from each other.
 */
abstract class Item
{
	protected function __construct(private Singleton $object, private int $count = 0) {
	}

	#[Pure] public function __toString(): string {
		return $this->count . ' ' . $this->object;
	}

	/**
	 * Get the item size.
	 */
	#[Pure] public function Count(): int {
		return $this->count;
	}

	/**
	 * Get the item object.
	 */
	#[Pure] public function getObject(): Singleton {
		return $this->object;
	}

	/**
	 * Add an item.
	 *
	 * @throws ItemException The item to be added has not the same object.
	 */
	public function addItem(Item $item): void {
		if ($item->object !== $this->object) {
			throw new ItemException($this, $item, ItemException::ADD_WRONG_ITEM);
		}
		$this->count += $item->count;
	}

	/**
	 * Remove an item.
	 *
	 * @throws ItemException The item to be removed has not the same object or is bigger than this item.
	 */
	public function removeItem(Item $item): void {
		if ($item->object !== $this->object) {
			throw new ItemException($this, $item, ItemException::REMOVE_WRONG_ITEM);
		}
		if ($item->count > $this->count) {
			throw new ItemException($this, $item, ItemException::REMOVE_TOO_MUCH);
		}
		$this->count -= $item->count;
	}
}
