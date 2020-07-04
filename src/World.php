<?php
declare (strict_types = 1);
namespace Lemuria;

use Lemuria\Exception\MapException;

/**
 * The world model of Lemuria is limited to a two-dimensional map, but flexible in terms of technical implementation.
 */
interface World extends Serializable
{
	const NORTH = 'N';

	const NORTHEAST = 'NE';

	const EAST = 'E';

	const SOUTHEAST = 'SE';

	const SOUTH = 'S';

	const SOUTHWEST = 'SW';

	const WEST = 'W';

	const NORTHWEST = 'NW';

	/**
	 * Get the world coordinates of a region.
	 *
	 * @param Location $location
	 * @return Coordinates
	 * @throws MapException
	 */
	public function getCoordinates(Location $location): Coordinates;

	/**
	 * Get the neighbour regions of a region.
	 *
	 * @param Location $location
	 * @return Neighbours
	 * @throws MapException
	 */
	public function getNeighbours(Location $location): Neighbours;

	/**
	 * Check if a direction is valid in this world.
	 *
	 * @param string $direction
	 * @return bool
	 */
	public function isDirection(string $direction): bool;

	/**
	 * Load the world data.
	 *
	 * @return self
	 */
	public function load(): self;

	/**
	 * Save the world data.
	 *
	 * @return self
	 */
	public function save(): self;
}
