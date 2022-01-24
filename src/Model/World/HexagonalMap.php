<?php
declare (strict_types = 1);
namespace Lemuria\Model\World;

use Lemuria\Exception\LemuriaException;
use Lemuria\Model\Location;
use Lemuria\Model\Neighbours;

/**
 * Representation of a two-dimensional world with six directions.
 */
final class HexagonalMap extends BaseMap
{
	/**
	 * @var string[]
	 */
	protected array $directions = [Direction::NORTHEAST, Direction::EAST, Direction::SOUTHEAST, Direction::SOUTHWEST,
								   Direction::WEST, Direction::NORTHWEST];

	/**
	 * Get the shortest distance between two regions.
	 */
	public function getDistance(Location $from, Location $to): int {
		$fromCoordinates = $this->getCoordinates($from);
		$toCoordinates   = $this->getCoordinates($to);
		if ($fromCoordinates->X() <= $toCoordinates->X()) {
			$left  = $fromCoordinates;
			$right = $toCoordinates;
		} else {
			$left  = $toCoordinates;
			$right = $fromCoordinates;
		}
		$distance = $right->X() - $left->X();

		$dy = $right->Y() - $left->Y();
		if ($dy > 0) {
			$distance += $dy;
		} else {
			$dy = abs($dy);
			if ($dy > $distance) {
				$distance += $dy - $distance;
			}
		}

		return $distance;
	}

	/**
	 * Get the neighbour regions of a location.
	 */
	public function getNeighbours(Location $location): Neighbours {
		$coordinates = $this->getCoordinates($location);
		$x           = $coordinates->X();
		$y           = $coordinates->Y();
		$neighbours  = new Neighbours();
		$this->setNeighbour(Direction::NORTHEAST, ++$y, $x, $neighbours);
		$this->setNeighbour(Direction::EAST, --$y, ++$x, $neighbours);
		$this->setNeighbour(Direction::SOUTHEAST, --$y, $x, $neighbours);
		$this->setNeighbour(Direction::SOUTHWEST, $y, --$x, $neighbours);
		$this->setNeighbour(Direction::WEST, ++$y, --$x, $neighbours);
		$this->setNeighbour(Direction::NORTHWEST, ++$y, $x, $neighbours);
		return $neighbours;
	}

	/**
	 * Get the path from a location to a distant point.
	 */
	public function getPath(Location $start, Direction $direction, int $distance): Path {
		return match ($direction) {
			Direction::NORTHEAST => $this->createDiagonalWays($start, $distance, 1, -1, 1, 0, 1),
			Direction::EAST      => $this->createEastWays($start, $distance),
			Direction::SOUTHEAST => $this->createDiagonalWays($start, $distance, -1, 0, -1, 1, 1),
			Direction::SOUTHWEST => $this->createDiagonalWays($start, $distance, -1, 1, -1, 0, -1),
			Direction::WEST      => $this->createWestWays($start, $distance),
			Direction::NORTHWEST => $this->createDiagonalWays($start, $distance, 1, 0, 1, -1, -1),
			default              => throw new LemuriaException()
		};
	}

	/**
	 * Create all possible ways east from location, including diagonals to north/south.
	 */
	private function createEastWays(Location $location, int $distance): Path {
		$path     = new Path();
		$basicWay = [$location];
		while ($distance-- > 0) {
			$next = $this->nextLocation($location, 0, 1);
			if (!$next) {
				return $path;
			}

			if ($distance > 0) {
				$diagonals = $this->createDiagonalWays($next, $distance, 1, -1, 1, 0, 1);
				foreach ($diagonals as $way) {
					$path[] = array_merge($basicWay, $way);
				}
				$diagonals = $this->createDiagonalWays($next, $distance, -1, 0, -1, 1, 1);
				foreach ($diagonals as $way) {
					$path[] = array_merge($basicWay, $way);
				}
				$basicWay[] = $next;
				$location   = $next;
			} else {
				$basicWay[] = $next;
				$path[]     = $basicWay;
			}
		}
		return $path;
	}

	/**
	 * Create all possible ways west from location, including diagonals to north/south.
	 */
	private function createWestWays(Location $location, int $distance): Path {
		$path     = new Path();
		$basicWay = [$location];
		while ($distance-- > 0) {
			$next = $this->nextLocation($location, 0, -1);
			if (!$next) {
				return $path;
			}

			if ($distance > 0) {
				$diagonals = $this->createDiagonalWays($next, $distance, 1, 0, 1, -1, -1);
				foreach ($diagonals as $way) {
					$path[] = array_merge($basicWay, $way);
				}
				$diagonals = $this->createDiagonalWays($next, $distance, -1, 1, -1, 0, -1);
				foreach ($diagonals as $way) {
					$path[] = array_merge($basicWay, $way);
				}
				$basicWay[] = $next;
				$location   = $next;
			} else {
				$basicWay[] = $next;
				$path[]     = $basicWay;
			}
		}
		return $path;
	}

	/**
	 * Create all diagonal ways in a 90° sector (NE, SE, SW, NW).
	 *
	 * @noinspection DuplicatedCode
	 */
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
