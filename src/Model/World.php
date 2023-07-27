<?php
declare (strict_types = 1);
namespace Lemuria\Model;

use Lemuria\Model\Exception\MapException;
use Lemuria\Model\World\Direction;
use Lemuria\Model\World\Path;
use Lemuria\Serializable;

/**
 * The world model of Lemuria is limited to a two-dimensional map, but flexible in terms of technical implementation.
 */
interface World extends Serializable
{
	/**
	 * Get the world coordinates of a region.
	 *
	 * @throws MapException
	 */
	public function getCoordinates(Location $location): Coordinates;

	/**
	 * Get the shortest distance between two regions.
	 *
	 * @throws MapException
	 */
	public function getDistance(Location $from, Location $to): int;

	/**
	 * Get the neighbour regions of a region.
	 */
	public function getNeighbours(Location $location): Neighbours;

	/**
	 * Get the path from a location to a distant point.
	 *
	 * @throws MapException
	 */
	public function getPath(Location $start, Direction $direction, int $distance): Path;

	/**
	 * Get the neighbours of a region in alternative directions.
	 */
	public function getAlternatives(Location $location, Direction $direction): Neighbours;

	/**
	 * Check if a direction is valid in this world.
	 */
	public function isDirection(Direction $direction): bool;

	/**
	 * Load the world data.
	 */
	public function load(): World;

	/**
	 * Save the world data.
	 */
	public function save(): World;
}
