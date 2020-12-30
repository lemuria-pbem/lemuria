<?php
declare (strict_types = 1);
namespace Lemuria\Model;

use JetBrains\PhpStorm\ExpectedValues;

use Lemuria\Model\Exception\MapException;
use Lemuria\Serializable;

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
	 * @throws MapException
	 */
	public function getCoordinates(Location $location): Coordinates;

	/**
	 * Get the neighbour regions of a region.
	 *
	 * @throws MapException
	 */
	public function getNeighbours(Location $location): Neighbours;

	/**
	 * Check if a direction is valid in this world.
	 */
	public function isDirection(#[ExpectedValues(valuesFromClass: self::class)] string $direction): bool;

	/**
	 * Load the world data.
	 */
	public function load(): World;

	/**
	 * Save the world data.
	 */
	public function save(): World;
}
