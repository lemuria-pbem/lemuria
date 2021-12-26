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
			World::NORTHEAST => $this->createDiagonalWays($start, $distance, 1, -1, 1, 0, 1),
			World::EAST      => $this->createWay($start, 1, $distance),
			World::SOUTHEAST => $this->createDiagonalWays($start, $distance, -1, 0, -1, 1, 1),
			World::SOUTHWEST => $this->createDiagonalWays($start, $distance, -1, 1, -1, 0, -1),
			World::WEST      => $this->createWay($start, -1, $distance),
			World::NORTHWEST => $this->createDiagonalWays($start, $distance, 1, 0, 1, -1, -1),
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

	private function createDiagonalWays(Location $location, int $distance,
		                                int $dy1, int $dx1, int $dy2, int $dx2, int $dx3): Path {
		$path = $this->createWays($location, $dy2, $dx2);
		if (!$path->count() || $distance <= 1) {
			return $path;
		}

		$basicWay = $path[0];
		$first    = $basicWay[1];
		$f        = 1;
		$path->offsetUnset(0);

		do {
			$way  = $basicWay;
			$last = $first;
			$i    = $f;
			do {
				if ($i++ % 2) {
					$next = $this->nextLocation($last, $dy1, $dx1);
				} else {
					$next = $this->nextLocation($last, $dy2, $dx2);
				}
				if ($next) {
					$way[] = $next;
					$last  = $next;
				}
			} while ($next && $i < $distance);
			if ($next) {
				$path[] = $way;
			}

			$way  = $basicWay;
			$last = $first;
			$i    = $f;
			do {
				if ($i++ % 2) {
					$next = $this->nextLocation($last, $dy2, $dx2);
				} else {
					$next = $this->nextLocation($last, $dy1, $dx1);
				}
				if ($next) {
					$way[] = $next;
					$last  = $next;
				}
			} while ($next && $i < $distance);
			if ($next) {
				$path[] = $way;
			}

			$first = $this->nextLocation($first, 0, $dx3);
		} while (++$f < $distance);

		if ($first) {
			$way    = $basicWay;
			$way[]  = $first;
			$path[] = $way;
		}

		return $path;
	}

	private function createWays(Location $location, int $dY, int $dX): Path {
		$path  = new Path();
		$first = $this->nextLocation($location, $dY, $dX);
		if ($first) {
			$path[0] = [$location, $first];
		}
		return $path;
	}

	private function nextLocation(Location $location, int $dY, int $dX): ?Location {
		$coordinates = $this->getCoordinates($location);
		$x           = $coordinates->X();
		$y           = $coordinates->Y();
		return $this->getByCoordinates($y + $dY, $x + $dX);
	}
}
