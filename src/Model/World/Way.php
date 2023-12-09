<?php
declare(strict_types = 1);
namespace Lemuria\Model\World;

use Lemuria\Model\Location;

class Way implements \ArrayAccess, \Countable, \Iterator
{
	/**
	 * @var array<int, Direction>
	 */
	protected array $directions = [];

	/**
	 * @var array<int, Location>
	 */
	protected array $locations = [];

	private int $index = 0;

	private int $count = 0;

	/**
	 * @param int $offset
	 */
	public function offsetExists(mixed $offset): bool {
		return isset($this->locations[$offset]);
	}

	/**
	 * @param int $offset
	 * @return Location
	 */
	public function offsetGet(mixed $offset): Location {
		return $this->locations[$offset];
	}

	/**
	 * @param Direction $offset
	 * @param Location $value
	 */
	public function offsetSet(mixed $offset, mixed $value): void {
		$this->directions[] = $offset;
		$this->locations[]  = $value;
		$this->count++;
	}

	/**
	 * @param int $offset
	 */
	public function offsetUnset(mixed $offset): void {
		unset($this->directions[$offset]);
		unset($this->locations[$offset]);
		$this->directions = array_values($this->directions);
		$this->locations  = array_values($this->locations);
		$this->count      = count($this->locations);
	}

	public function count(): int {
		return $this->count;
	}

	public function current(): Location {
		return $this->locations[$this->index];
	}

	public function key(): Direction {
		return $this->directions[$this->index];
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

	public function clone(): static {
		return clone $this;
	}

	public function last(): Location {
		return $this->locations[$this->count - 1];
	}

	public function merge(Way $way): static {
		$merged             = new self();
		$merged->directions = array_merge($this->directions, $way->directions);
		$merged->locations  = array_merge($this->locations, $way->locations);
		$merged->count      = count($merged->locations);
		return $merged;
	}
}
