<?php
declare (strict_types = 1);
namespace Lemuria\Model;

use JetBrains\PhpStorm\Pure;

use Lemuria\Model\Exception\NeighbourException;

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
	 */
	#[Pure] public function offsetExists(mixed $offset): bool {
		return isset($this->locations[$offset]);
	}

	/**
	 * Get the location in the specified direction.
	 */
	#[Pure] public function offsetGet(mixed $offset): ?Location {
		return $this->locations[$offset] ?? null;
	}

	/**
	 * Set the location in the specified direction.
	 */
	public function offsetSet(mixed $offset, mixed $value): void {
		$this->locations[$offset] = $value;
	}

	/**
	 * Unset the location in the specified direction.
	 */
	public function offsetUnset(mixed $offset): void {
		unset($this->locations[$offset]);
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
	public function getDirection(Location $neighbour): string
	{
		foreach ($this->locations as $direction => $location /* @var Location $location */) {
			if ($location === $neighbour) {
				return $direction;
			}
		}
		throw new NeighbourException($neighbour);
	}
}
