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
	 * @var array<string>
	 */
	private array $list = [];

	/**
	 * @var int $offset
	 */
	public function offsetExists(mixed $offset): bool {
		return isset($this->list[$offset]);
	}

	/**
	 * @var int $offset
	 */
	public function offsetGet(mixed $offset): string {
		if ($this->offsetExists($offset)) {
			return $this->list[$offset];
		}
		throw new \OutOfBoundsException();
	}

	/**
	 * @param int $offset
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

	/**
	 * @param int $offset
	 */
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
	 * @return array<string>
	 */
	public function serialize(): array {
		return $this->list;
	}

	/**
	 * Restore the list from serialized data.
	 *
	 * @param array<string> $data
	 */
	public function unserialize(array $data): static {
		$this->list  = array_values($data);
		$this->index = 0;
		$this->count = count($this->list);
		return $this;
	}

	/**
	 * Clear the list.
	 */
	public function clear(): static {
		$this->list  = [];
		$this->index = 0;
		$this->count = 0;
		return $this;
	}
}
