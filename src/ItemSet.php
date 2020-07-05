<?php
declare (strict_types = 1);
namespace Lemuria;

use Lemuria\Exception\ItemSetException;
use Lemuria\Exception\ItemSetFillException;
use Lemuria\Exception\LemuriaException;
use Lemuria\Exception\UnserializeItemSetException;

/**
 * Implementation of a set of items, where an item is a quantity of something.
 */
abstract class ItemSet implements \ArrayAccess, \Countable, \Iterator, Serializable
{
	/**
	 * @var string[]
	 */
	private array $indices = [];

	/**
	 * @var array(string=>Item)
	 */
	private array $items = [];

	/**
	 * @var int
	 */
	private int $index = 0;

	/**
	 * @var int
	 */
	private int $count = 0;

	/**
	 * Check if an item is in the set.
	 *
	 * @param Singleton|string $offset
	 * @return bool
	 */
	public function offsetExists($offset): bool {
		$class = $this->getClass($offset);
		return isset($this->items[$class]);
	}

	/**
	 * Get an item from the set.
	 *
	 * If no such item exists, an empty item is returned.
	 *
	 * @param Singleton|string $offset
	 * @return Item
	 */
	public function offsetGet($offset): Item {
		$class = $this->getClass($offset);
		if (isset($this->items[$class])) {
			return $this->items[$class];
		}
		return new NullItem($offset);
	}

	/**
	 * Set or replace an item the set.
	 *
	 * @param Item|Singleton|string $offset
	 * @param Item $value
	 */
	public function offsetSet($offset, $value): void {
		if (!$this->isValidItem($value)) {
			throw new LemuriaException('Invalid item for this set: ' . $value);
		}

		if ($offset instanceof Item) {
			$offset = $this->getClass($offset->getObject());
		}
		$class = $this->getClass($offset);
		if (isset($this->items[$class])) {
			$this->items[$class] = $value;
		} else {
			$this->addItem($value);
		}
	}

	/**
	 * Delete an item from the set.
	 *
	 * @param mixed $offset
	 */
	public function offsetUnset($offset): void {
		$class = $this->getClass($offset);
		if (isset($this->items[$class])) {
			$this->delete($class);
		}
	}

	/**
	 * Get the number of items in the set.
	 *
	 * @return int
	 */
	public function count(): int {
		return $this->count;
	}

	/**
	 * Get the current item of the iterator.
	 *
	 * @return Item
	 */
	public function current(): ?Item {
		$key = $this->key();
		return $key !== null ? $this->items[$key] : null;
	}

	/**
	 * Get the current key of the iterator.
	 *
	 * @return string
	 */
	public function key(): ?string {
		return $this->indices[$this->index] ?? null;
	}

	/**
	 * Increment the iterator.
	 */
	public function next(): void {
		$this->index++;
	}

	/**
	 * Rewind the iterator.
	 */
	public function rewind(): void {
		$this->index = 0;
	}

	/**
	 * Check if the iterator's current item is valid.
	 *
	 * @return bool
	 */
	public function valid(): bool {
		return $this->index < $this->count;
	}

	/**
	 * Get a plain data array of the model's data.
	 *
	 * @return array
	 */
	public function serialize(): array {
		$data = [];
		foreach ($this->items as $class => $item /* @var Item $item */) {
			$data[$class] = $item->Count();
		}
		return $data;
	}

	/**
	 * Restore the model's data from serialized data.
	 *
	 * @param array $data
	 * @return Serializable
	 */
	public function unserialize(array $data): Serializable {
		$this->clear();
		foreach ($data as $class => $count) {
			if (!is_string($class) || !is_int($count)) {
				throw new UnserializeItemSetException();
			}
			$this->addItem($this->createItem($class, $count));
		}
		return $this;
	}

	/**
	 * Clear the set.
	 *
	 * @return ItemSet
	 */
	public function clear(): ItemSet {
		$this->indices = [];
		$this->items   = [];
		$this->index   = 0;
		$this->count   = 0;
		return $this;
	}

	/**
	 * Fill the set with a copy of the items of given set.
	 *
	 * @param ItemSet $set
	 * @return ItemSet
	 */
	public function fill(ItemSet $set): ItemSet {
		foreach ($set->items as $item) {
			if (!$this->isValidItem($item)) {
				throw new ItemSetFillException($item, $this);
			}
			$this->addItem($item);
		}
		return $this;
	}

	/**
	 * Create an Item from unserialized data.
	 *
	 * @param string $class
	 * @param int $count
	 * @return Item
	 */
	abstract protected function createItem(string $class, int $count): Item;

	/**
	 * Check if an item is valid for this set.
	 *
	 * @param Item $item
	 * @return bool
	 */
	abstract protected function isValidItem($item): bool;

	/**
	 * Add an item to the set.
	 *
	 * @param Item $item
	 */
	protected function addItem(Item $item): void {
		if ($item->Count() > 0) {
			$class = getClass($item->getObject());
			if (isset($this->items[$class])) {
				$currentItem = $this->items[$class]; /* @var Item $currentItem */
				$currentItem->addItem($item);
			} else {
				$this->items[$class] = $item;
				$this->indices[]     = $class;
				$this->count++;
			}
		}
	}

	/**
	 * Remove an item from the set.
	 *
	 * @param Item $item
	 * @throws ItemSetException The item is not part of the set.
	 */
	protected function removeItem(Item $item): void {
		if ($item->Count() > 0) {
			$class = getClass($item->getObject());
			if (!isset($this->items[$class])) {
				throw new ItemSetException($item);
			}
			$currentItem = $this->items[$class]; /* @var Item $currentItem */
			$currentItem->removeItem($item);
			if ($currentItem->Count() === 0) {
				$this->delete($class);
			}
		}
	}

	/**
	 * Remove the item of the specified class from the set.
	 *
	 * @param string $class
	 */
	private function delete(string $class): void {
		unset($this->items[$class]);
		$this->indices = array_keys($this->items);
		$this->count--;
		if ($this->index >= $this->count) {
			if ($this->count === 0) {
				$this->index = 0;
			} else {
				$this->index--;
			}
		}
	}

	/**
	 * Get the class of an offset.
	 *
	 * @param Singleton|string $offset
	 * @return string
	 */
	private function getClass($offset): string {
		return $offset instanceof Singleton ? getClass($offset) : getClass((string)$offset);
	}
}
