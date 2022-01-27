<?php
declare (strict_types = 1);
namespace Lemuria\Model;

use JetBrains\PhpStorm\Pure;

use Lemuria\Model\Exception\NeighbourException;
use Lemuria\Model\World\Direction;

/**
 * A helper class that encapsulates the neighbour locations of a location.
 */
class Neighbours implements \ArrayAccess, \Countable
{
	/**
	 * @var array(string=>Location)
	 */
	private array $locations = [];

	/**
	 * Check if a location in the specified direction exists.
	 *
	 * @param Direction|string $offset
	 */
	#[Pure] public function offsetExists(mixed $offset): bool {
		return isset($this->locations[$this->offset($offset)]);
	}

	/**
	 * Get the location in the specified direction.
	 *
	 * @param Direction|string $offset
	 */
	#[Pure] public function offsetGet(mixed $offset): ?Location {
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
	#[Pure] public function count(): int {
		return count($this->locations);
	}

	/**
	 * Get all neighbors.
	 *
	 * @return array(string=>Location)
	 */
	#[Pure] public function getAll(): array {
		return $this->locations;
	}

	/**
	 * Get the direction to a neighbour location.
	 *
	 * @throws NeighbourException
	 */
	public function getDirection(Location $neighbour): Direction
	{
		foreach ($this->locations as $direction => $location /* @var Direction $direction */) {
			if ($location === $neighbour) {
				return $direction;
			}
		}
		throw new NeighbourException($neighbour);
	}

	private function offset(mixed $offset): string {
		return $offset instanceof Direction ? $offset->value : $offset;
	}
}
