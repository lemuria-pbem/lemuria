<?php
declare (strict_types = 1);
namespace Lemuria\Model\World;

use Lemuria\Exception\LemuriaException;
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

	/**
	 * Get the path from a location to a distant point.
	 */
	public function getPath(Location $start, string $direction, int $distance): Path {
		return match ($direction) {
			World::NORTHEAST => $this->createWays($start, 1, 0, $distance),
			World::EAST      => $this->createWay($start, 1, $distance),
			World::SOUTHEAST => $this->createWays($start, -1, 1, $distance),
			World::SOUTHWEST => $this->createWays($start, -1, 0, $distance),
			World::WEST      => $this->createWay($start, -1, $distance),
			World::NORTHWEST => $this->createWays($start, 1, -1, $distance),
			default          => throw new LemuriaException()
		};
	}

	private function createWay(Location $location, int $dX, int $distance): Path {
		$path        = new Path();
		$way         = [$location];
		$coordinates = $this->getCoordinates($location);
		$x           = $coordinates->X();
		$y           = $coordinates->Y();
		while ($distance-- > 0) {
			$x       += $dX;
			$location = $this->getByCoordinates($y, $x);
			if (!$location) {
				return $path;
			}
			$way[] = $location;
		}
		$path[0] = $way;
		return $path;
	}

	private function createWays(Location $location, int $dY, int $dX, int $distance): Path {
		$path        = new Path();
		$i           = 0;
		$coordinates = $this->getCoordinates($location);
		$x           = $coordinates->X();
		$y           = $coordinates->Y();
		$first       = $this->getByCoordinates($y + $dY, $x + $dX);
		if (!$first) {
			return $path;
		}

		$way = [$location, $first];
		if (count($way) > $distance) {
			$path[$i++] = $way;
		}
		//TODO
		return $path;
	}
}
