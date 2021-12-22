<?php
declare (strict_types = 1);
namespace Lemuria\Model\World;

use Lemuria\Exception\LemuriaException;
use Lemuria\Model\Location;
use Lemuria\Model\Neighbours;
use Lemuria\Model\World;

/**
 * Representation of a two-dimensional world with eight directions.
 */
final class OctagonalMap extends BaseMap
{
	/**
	 * Get the neighbour regions of a location.
	 */
	public function getNeighbours(Location $location): Neighbours {
		$coordinates = $this->getCoordinates($location);
		$x           = $coordinates->X();
		$y           = $coordinates->Y();
		$neighbours  = new Neighbours();
		$this->setNeighbour(World::NORTH, ++$y, $x, $neighbours);
		$this->setNeighbour(World::NORTHEAST, $y, ++$x, $neighbours);
		$this->setNeighbour(World::EAST, --$y, $x, $neighbours);
		$this->setNeighbour(World::SOUTHEAST, --$y, $x, $neighbours);
		$this->setNeighbour(World::SOUTH, $y, --$x, $neighbours);
		$this->setNeighbour(World::SOUTHWEST, $y, --$x, $neighbours);
		$this->setNeighbour(World::WEST, ++$y, $x, $neighbours);
		$this->setNeighbour(World::NORTHWEST, ++$y, $x, $neighbours);
		return $neighbours;
	}

	/**
	 * Get the path from a location to a distant point.
	 */
	public function getPath(Location $start, string $direction, int $distance): Path {
		return match ($direction) {
			World::NORTH     => $this->createWay($start, 1, 0, $distance),
			World::NORTHEAST => $this->createWays($start, 1, 1, $distance),
			World::EAST      => $this->createWay($start, 0, 1, $distance),
			World::SOUTHEAST => $this->createWays($start, -1, 1, $distance),
			World::SOUTH     => $this->createWay($start, -1, 0, $distance),
			World::SOUTHWEST => $this->createWays($start, -1, -1, $distance),
			World::WEST      => $this->createWay($start, 0, -1, $distance),
			World::NORTHWEST => $this->createWays($start, 1, -1, $distance),
			default          => throw new LemuriaException()
		};
	}

	private function createWay(Location $location, int $dY, int $dX, int $distance): Path {
		$path        = new Path();
		$way         = [$location];
		$coordinates = $this->getCoordinates($location);
		$x           = $coordinates->X();
		$y           = $coordinates->Y();
		while ($distance-- > 0) {
			$x       += $dX;
			$y       += $dY;
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
		$nY          = 1;
		do {
			$way = [$location];
			$nX  = 1;
			while (true) {
				$d = (int)round(sqrt($nX ** 2 + $nY ** 2));
				if ($d > $distance) {
					break;
				}
				$next = $this->getByCoordinates($y + $nY * $dY, $x + $nX * $dX);
				if ($next) {
					$way[] = $next;
					$nX++;
				} else {
					break;
				}
			}
			if (count($way) >= 2) {
				$path[$i++] = $way;
			}
			$nY++;
		} while ($nY <= $distance);
		return $path;
	}
}
