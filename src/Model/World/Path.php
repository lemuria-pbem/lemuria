<?php
declare(strict_types = 1);
namespace Lemuria\Model\World;

class Path implements \ArrayAccess, \Countable, \Iterator
{
	/**
	 * @var array<Way>
	 */
	protected array $ways = [];

	private int $index = 0;

	private int $count = 0;

	/**
	 * @param int $offset
	 */
	public function offsetExists(mixed $offset): bool {
		return isset($this->ways[$offset]);
	}

	/**
	 * @param int $offset
	 */
	public function offsetGet(mixed $offset): Way {
		return $this->ways[$offset];
	}

	/**
	 * @param int $offset
	 * @param Way $value
	 */
	public function offsetSet(mixed $offset, mixed $value): void {
		if ($offset === null) {
			$offset = $this->count;
		}
		$this->ways[$offset] = $value;
		$this->count++;
	}

	/**
	 * @param int $offset
	 */
	public function offsetUnset(mixed $offset): void {
		unset($this->ways[$offset]);
		$this->ways  = array_values($this->ways);
		$this->count = count($this->ways);
	}

	public function count(): int {
		return $this->count;
	}

	public function current(): Way {
		return $this->ways[$this->index];
	}

	public function key(): int {
		return $this->index;
	}

	public function next(): void {
		$this->index++;
	}

	public function rewind(): void {
		$this->index = 0;
	}

	public function valid(): bool {
		return $this->index < $this->count;
	}
}
