<?php
declare (strict_types = 1);
namespace Lemuria\Model;

/**
 * A helper class that encapsulates the neighbour locations of a location.
 */
class Neighbours implements \ArrayAccess
{
	/**
	 * @var array(string=>Location)
	 */
	private array $locations = [];

	/**
	 * Check if a location in the specified direction exists.
	 *
	 * @param string $offset
	 * @return bool
	 */
	public function offsetExists($offset): bool {
		return isset($this->locations[$offset]);
	}

	/**
	 * Get the location in the specified direction.
	 *
	 * @param string $offset
	 * @return Location
	 */
	public function offsetGet($offset): ?Location {
		return $this->locations[$offset] ?? null;
	}

	/**
	 * Set the location in the specified direction.
	 *
	 * @param string $offset
	 * @param Location $value
	 */
	public function offsetSet($offset, $value): void {
		$this->locations[$offset] = $value;
	}

	/**
	 * Unset the location in the specified direction.
	 *
	 * @param string $offset
	 */
	public function offsetUnset($offset): void {
		unset($this->locations[$offset]);
	}

	/**
	 * Get all neighbors.
	 *
	 * @return array(string=>Location)
	 */
	public function getAll(): array {
		return $this->locations;
	}
}
