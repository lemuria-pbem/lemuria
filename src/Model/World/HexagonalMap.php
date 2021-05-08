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
	 */
	public function getNeighbours(Location $location): Neighbours {
		$coordinates = $this->getCoordinates($location);
		$x           = $coordinates->X();
		$y           = $coordinates->Y();
		$neighbours  = new Neighbours();
		$this->setNeighbour(World::NORTHEAST, ++$y, $x, $neighbours);
		$this->setNeighbour(World::EAST, --$y, ++$x, $neighbours);
		$this->setNeighbour(World::SOUTHEAST, --$y, $x, $neighbours);
		$this->setNeighbour(World::SOUTHWEST, $y, --$x, $neighbours);
		$this->setNeighbour(World::WEST, ++$y, --$x, $neighbours);
		$this->setNeighbour(World::NORTHWEST, ++$y, $x, $neighbours);
		return $neighbours;
	}
}
