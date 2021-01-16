<?php
declare(strict_types = 1);
namespace Lemuria;

use JetBrains\PhpStorm\Pure;
use Lemuria\Engine\Instructions;

class StringList implements Instructions
{
	use SerializableTrait;

	/**
	 * @var string[]
	 */
	private array $list = [];

	private int $index = 0;

	private int $count = 0;

	/**
	 * @param int $offset
	 */
	#[Pure]
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

	#[Pure]
	public function count(): int {
		return $this->count;
	}

	public function current(): string {
		return $this->offsetGet($this->index);
	}

	public function next(): void {
		$this->index++;
	}

	#[Pure]
	public function key(): int {
		return $this->index;
	}

	#[Pure]
	public function valid(): bool {
		return $this->index < $this->count;
	}

	public function rewind(): void {
		$this->index = 0;
	}

	/**
	 * Get a plain data array.
	 *
	 * @return string[]
	 */
	#[Pure] public function serialize(): array {
		return $this->list;
	}

	/**
	 * Restore the list from serialized data.
	 *
	 * @param string[] $data
	 */
	public function unserialize(array $data): Serializable {
		$this->list = array_values($data);
		$this->index = 0;
		$this->count = count($this->list);
		return $this;
	}
}
