<?php
declare (strict_types = 1);
namespace Lemuria\Model\World;

use Lemuria\Model\Location;
use Lemuria\Model\Neighbours;
use Lemuria\Model\World;

/**
 * Representation of a two-dimensional world with six directions.
 */
final class HexagonalMap extends BaseMap
{
	/**
	 * @var string[]
	 */
	protected array $directions = [World::NORTHEAST, World::EAST, World::SOUTHEAST, World::SOUTHWEST, World::WEST,
								   World::NORTHWEST];

	/**
	 * Get the neighbour regions of a location.
	 *
	 * @param Location $location
	 * @return Neighbours
	 */
	public function getNeighbours(Location $location): Neighbours {
		$coordinates = $this->getCoordinates($location);
		$x           = $coordinates->X();
		$y           = $coordinates->Y();
		$neighbours  = new Neighbours();
		$neighbours[World::NORTHEAST] = $this->getLocation($this->map[++$y][$x]) ?? null;
		$neighbours[World::EAST]      = $this->getLocation($this->map[--$y][++$x]) ?? null;
		$neighbours[World::SOUTHEAST] = $this->getLocation($this->map[--$y][$x]) ?? null;
		$neighbours[World::SOUTHWEST] = $this->getLocation($this->map[$y][--$x]) ?? null;
		$neighbours[World::WEST]      = $this->getLocation($this->map[++$y][--$x]) ?? null;
		$neighbours[World::NORTHWEST] = $this->getLocation($this->map[++$y][$x]) ?? null;
		return $neighbours;
	}
}
