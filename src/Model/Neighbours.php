<?php
declare (strict_types = 1);
namespace Lemuria\Model;

use Lemuria\Model\Exception\NeighbourException;
use Lemuria\Model\World\Direction;

/**
 * A helper class that encapsulates the neighbour locations of a location.
 *
 * @\ArrayAccess<Direction, Location>
 * @\Iterator<Direction, Location>
 */
class Neighbours implements \ArrayAccess, \Countable, \Iterator
{
	/**
	 * @var array<string, Location>
	 */
	private array $locations = [];

	/**
	 * @var string[]
	 */
	private array $indices;

	private int $index;

	/**
	 * Check if a location in the specified direction exists.
	 *
	 * @param Direction|string $offset
	 */
	public function offsetExists(mixed $offset): bool {
		return isset($this->locations[$this->offset($offset)]);
	}

	/**
	 * Get the location in the specified direction.
	 *
	 * @param Direction|string $offset
	 */
	public function offsetGet(mixed $offset): ?Location {
		return $this->locations[$this->offset($offset)] ?? null;
	}

	/**
	 * Set the location in the specified direction.
	 *
	 * @param Direction|string $offset
	 * @param Location $value
	 */
	public function offsetSet(mixed $offset, mixed $value): void {
		$this->locations[$this->offset($offset)] = $value;
	}

	/**
	 * Unset the location in the specified direction.
	 *
	 * @param Direction|string $offset
	 */
	public function offsetUnset(mixed $offset): void {
		unset($this->locations[$this->offset($offset)]);
	}

	/**
	 * Count the locations.
	 */
	public function count(): int {
		return count($this->locations);
	}

	public function current(): Location {
		return $this->locations[$this->indices[$this->index]];
	}

	public function key(): Direction {
		return Direction::from($this->indices[$this->index]);
	}

	public function next(): void {
		$this->index++;
	}

	public function rewind(): void {
		$this->index   = 0;
		$this->indices = array_keys($this->locations);
	}

	public function valid(): bool {
		return $this->index < count($this->indices);
	}

	/**
	 * @return Direction[]
	 */
	public function getDirections(): array {
		$directions = [];
		foreach (array_keys($this->locations) as $direction) {
			$directions[] = Direction::from($direction);
		}
		return $directions;
	}

	/**
	 * Get the direction to a neighbour location.
	 *
	 * @throws NeighbourException
	 */
	public function getDirection(Location $neighbour): Direction
	{
		foreach ($this->locations as $direction => $location) {
			if ($location === $neighbour) {
				return Direction::from($direction);
			}
		}
		throw new NeighbourException($neighbour);
	}

	private function offset(mixed $offset): string {
		return $offset instanceof Direction ? $offset->value : $offset;
	}
}
