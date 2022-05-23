<?php
declare(strict_types = 1);
namespace Lemuria;

use Lemuria\Engine\Instructions;

class StringList implements Instructions
{
	use CountableTrait;
	use IteratorTrait;
	use SerializableTrait;

	/**
	 * @var string[]
	 */
	private array $list = [];

	public function offsetExists(mixed $offset): bool {
		return isset($this->list[$offset]);
	}

	public function offsetGet(mixed $offset): string {
		if ($this->offsetExists($offset)) {
			return $this->list[$offset];
		}
		throw new \OutOfBoundsException();
	}

	/**
	 * @param \Stringable|string $value
	 */
	public function offsetSet(mixed $offset, mixed $value): void {
		if ($this->offsetExists($offset)) {
			$this->list[$offset] = (string)$value;
		} else {
			$this->list[] = (string)$value;
			$this->count++;
		}
	}

	public function offsetUnset(mixed $offset): void {
		if ($this->offsetExists($offset)) {
			unset($this->list[$offset]);
			$this->count--;
			$this->list = array_values($this->list);
		}
	}

	public function current(): string {
		return $this->offsetGet($this->index);
	}

	/**
	 * Get a plain data array.
	 *
	 * @return string[]
	 */
	public function serialize(): array {
		return $this->list;
	}

	/**
	 * Restore the list from serialized data.
	 *
	 * @param string[] $data
	 */
	public function unserialize(array $data): Serializable {
		$this->list  = array_values($data);
		$this->index = 0;
		$this->count = count($this->list);
		return $this;
	}

	/**
	 * Clear the list.
	 */
	public function clear(): Instructions {
		$this->list  = [];
		$this->index = 0;
		$this->count = 0;
		return $this;
	}
}
