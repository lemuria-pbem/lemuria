<?php
declare(strict_types = 1);
namespace Lemuria\Model\World;

use Lemuria\Exception\LemuriaException;
use Lemuria\Model\Location;

class Path implements \ArrayAccess, \Countable, \Iterator
{
	/**
	 * @var array[]
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
	 * @return Location[]
	 */
	public function offsetGet(mixed $offset): array {
		return $this->ways[$offset];
	}

	/**
	 * @param int $offset
	 * @param Location[] $value
	 */
	public function offsetSet(mixed $offset, mixed $value): void {
		if (!is_array($value)) {
			throw new LemuriaException();
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

	/**
	 * @return Location[]
	 */
	public function current(): array {
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
