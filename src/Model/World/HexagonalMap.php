<?php
declare (strict_types = 1);
namespace Lemuria\Model\World;

use function Lemuria\sign;
use Lemuria\Exception\LemuriaException;
use Lemuria\Model\Coordinates;
use Lemuria\Model\Location;

/**
 * Representation of a two-dimensional world with six directions.
 */
final class HexagonalMap extends BaseMap
{
	private const array DIRECTION_ANGLE = [
		Direction::None->value      => NAN,
		Direction::North->value     => NAN,
		Direction::Northeast->value => M_PI / 3.0,
		Direction::East->value      => 0.0,
		Direction::Southeast->value => -M_PI / 3.0,
		Direction::South->value     => NAN,
		Direction::Southwest->value => M_PI + M_PI / 3.0,
		Direction::West->value      => M_PI,
		Direction::Northwest->value => M_PI - M_PI / 3.0
	];

	private const float GRADIENT_THRESHOLD = 0.5;

	/**
	 * @var array<string>
	 */
	protected array $directions = [Direction::Northeast, Direction::East, Direction::Southeast,
		                           Direction::Southwest, Direction::West, Direction::Northwest];

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
		if ($this->geometry === Geometry::Spherical) {
			$half = (int)ceil($this->width / 2);
			if ($distance > $half) {
				$distance = $this->width - $distance;
			}
		}

		$dy = $right->Y() - $left->Y();
		if ($this->geometry === Geometry::Spherical) {
			$absDy = abs($dy);
			$half  = (int)ceil($this->height / 2);
			if ($absDy > $half) {
				$dy = -1 * sign($dy) * ($this->height - $absDy);
			}
		}
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
	 * Get the path from a location to a distant point.
	 */
	public function getPath(Location $start, Direction $direction, int $distance): Path {
		return match ($direction) {
			Direction::Northeast => $this->createDiagonalWays($start, $distance, Direction::Northwest, $direction, 1, -1, 1, 0, 1),
			Direction::East      => $this->createEastWays($start, $distance),
			Direction::Southeast => $this->createDiagonalWays($start, $distance, Direction::Southwest, $direction, -1, 0, -1, 1, 1),
			Direction::Southwest => $this->createDiagonalWays($start, $distance, Direction::Southeast, $direction, -1, 1, -1, 0, -1),
			Direction::West      => $this->createWestWays($start, $distance),
			Direction::Northwest => $this->createDiagonalWays($start, $distance, Direction::Northeast, $direction, 1, 0, 1, -1, -1),
			default              => throw new LemuriaException()
		};
	}

	/**
	 * @return array<string, Coordinates>
	 */
	protected function getNeighbourCoordinates(Location $location): array {
		$coordinates = $this->getCoordinates($location);
		$x           = $coordinates->X();
		$y           = $coordinates->Y();
		return [
			Direction::Northeast->value => new MapCoordinates($x, ++$y),
			Direction::East->value      => new MapCoordinates(++$x, --$y),
			Direction::Southeast->value => new MapCoordinates($x, --$y),
			Direction::Southwest->value => new MapCoordinates(--$x, $y),
			Direction::West->value      => new MapCoordinates(--$x, ++$y),
			Direction::Northwest->value => new MapCoordinates($x, ++$y)
		];
	}

	/**
	 * @return array<string, Coordinates>
	 */
	protected function getAlternativeCoordinates(Location $location, Direction $direction): array {
		$coordinates = $this->getCoordinates($location);
		$x           = $coordinates->X();
		$y           = $coordinates->Y();
		return match ($direction) {
			Direction::Northeast => [
				Direction::Northwest->value => new MapCoordinates($x - 1, $y + 1),
				Direction::East->value      => new MapCoordinates($x + 1, $y)
			],
			Direction::East => [
				Direction::Northeast->value => new MapCoordinates($x, $y + 1),
				Direction::Southeast->value => new MapCoordinates($x + 1, $y - 1)
			],
			Direction::Southeast => [
				Direction::East->value      => new MapCoordinates($x + 1, $y),
				Direction::Southwest->value => new MapCoordinates($x, $y - 1)
			],
			Direction::Southwest => [
				Direction::Southeast->value => new MapCoordinates($x + 1, $y - 1),
				Direction::West->value      => new MapCoordinates($x - 1, $y)
			],
			Direction::West => [
				Direction::Southwest->value => new MapCoordinates($x, $y - 1),
				Direction::Northwest->value => new MapCoordinates($x - 1, $y + 1)
			],
			Direction::Northwest => [
				Direction::West->value      => new MapCoordinates($x - 1, $y),
				Direction::Northeast->value => new MapCoordinates($x, $y + 1)
			],
			default => throw new LemuriaException()
		};
	}

	protected function calculate2DPosition(float &$x, float &$y, Direction $direction): float {
		$angle = self::DIRECTION_ANGLE[$direction->value];
		if (!is_nan($angle)) {
			$x += cos($angle);
			$y += sin($angle);
		}
		return $angle;
	}

	protected function calculateDirectionFrom2D(float $x, float $y): Direction {
		if ($y >= 0) {
			if ($x >= 0) {
				return $this->calculateDirectionByGradient($x, $y, Direction::East, Direction::Northeast);
			}
			return $this->calculateDirectionByGradient(-$x, $y, Direction::West, Direction::Northwest);
		}
		if ($x >= 0) {
			return $this->calculateDirectionByGradient($x, -$y, Direction::East, Direction::Southeast);
		}
		return $this->calculateDirectionByGradient(-$x, -$y, Direction::West, Direction::Southwest);
	}

	/**
	 * Create all possible ways east from location, including diagonals to north/south.
	 */
	private function createEastWays(Location $location, int $distance): Path {
		$path     = new Path();
		$basicWay = new Way();
		$basicWay->offsetSet(Direction::None, $location);
		while ($distance-- > 0) {
			$next = $this->nextLocation($location, 0, 1);
			if (!$next) {
				return $path;
			}

			if ($distance > 0) {
				$diagonals = $this->createDiagonalWays($next, $distance, Direction::Northwest, Direction::Northeast, 1, -1, 1, 0, 1, false);
				foreach ($diagonals as $way) {
					$path[] = $basicWay->merge($way);
				}
				$diagonals = $this->createDiagonalWays($next, $distance, Direction::Southwest, Direction::Southeast, -1, 0, -1, 1, 1, false);
				foreach ($diagonals as $way) {
					$path[] = $basicWay->merge($way);
				}
				$basicWay->offsetSet(Direction::East, $next);
				$location = $next;
			} else {
				$basicWay->offsetSet(Direction::East, $next);
				$path[] = $basicWay;
			}
		}
		return $path;
	}

	/**
	 * Create all possible ways west from location, including diagonals to north/south.
	 */
	private function createWestWays(Location $location, int $distance): Path {
		$path     = new Path();
		$basicWay = new Way();
		$basicWay->offsetSet(Direction::None, $location);
		while ($distance-- > 0) {
			$next = $this->nextLocation($location, 0, -1);
			if (!$next) {
				return $path;
			}

			if ($distance > 0) {
				$diagonals = $this->createDiagonalWays($next, $distance, Direction::Northeast, Direction::Northwest, 1, 0, 1, -1, -1 ,false);
				foreach ($diagonals as $way) {
					$path[] = $basicWay->merge($way);
				}
				$diagonals = $this->createDiagonalWays($next, $distance, Direction::Southeast, Direction::Southwest, -1, 1, -1, 0, -1, false);
				foreach ($diagonals as $way) {
					$path[] = $basicWay->merge($way);
				}
				$basicWay->offsetSet(Direction::West, $next);
				$location = $next;
			} else {
				$basicWay->offsetSet(Direction::West, $next);
				$path[] = $basicWay;
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
		                                Direction $direction1, Direction $direction2,
		                                int $dy1, int $dx1, int $dy2, int $dx2, int $dx3,
		                                bool $firstIsNone = true): Path {
		$path = $this->createWays($location, $dy2, $dx2, $direction2, $firstIsNone);
		if (!$path->count() || $distance <= 1) {
			return $path;
		}

		$basicWay = $path->offsetGet(0);
		$first    = $basicWay->offsetGet(1);
		$f        = 1;
		$path->offsetUnset(0);

		do {
			$way  = clone $basicWay;
			$last = $first;
			$i    = $f;
			do {
				if ($i++ % 2) {
					$next      = $this->nextLocation($last, $dy1, $dx1);
					$direction = $direction1;
				} else {
					$next      = $this->nextLocation($last, $dy2, $dx2);
					$direction = $direction2;
				}
				if ($next) {
					$way->offsetSet($direction, $next);
					$last = $next;
				}
			} while ($next && $i < $distance);
			if ($next) {
				$path[] = $way;
			}

			$way  = clone $basicWay;
			$last = $first;
			$i    = $f;
			do {
				if ($i++ % 2) {
					$next      = $this->nextLocation($last, $dy2, $dx2);
					$direction = $direction2;
				} else {
					$next      = $this->nextLocation($last, $dy1, $dx1);
					$direction = $direction1;
				}
				if ($next) {
					$way->offsetSet($direction, $next);
					$last = $next;
				}
			} while ($next && $i < $distance);
			if ($next) {
				$path[] = $way;
			}

			$first = $this->nextLocation($first, 0, $dx3);
		} while (++$f < $distance);

		if ($first) {
			$basicWay->offsetSet($direction2, $first);
			$path[] = $basicWay;
		}

		return $path;
	}

	private function createWays(Location $location, int $dY, int $dX, Direction $direction, bool $firstIsNone): Path {
		$path  = new Path();
		$first = $this->nextLocation($location, $dY, $dX);
		if ($first) {
			$way = new Way();
			$way->offsetSet($firstIsNone ? Direction::None : $direction, $location);
			$way->offsetSet($direction, $first);
			$path[0] = $way;
		}
		return $path;
	}

	private function nextLocation(Location $location, int $dY, int $dX): ?Location {
		$coordinates = $this->getCoordinates($location);
		$x           = $coordinates->X();
		$y           = $coordinates->Y();
		return $this->getByCoordinates($y + $dY, $x + $dX);
	}

	private function calculateDirectionByGradient(float $x, float $y, Direction $lowGradient, Direction $highGradient): Direction {
		if ($x > 0) {
			$gradient = $y / $x;
			return $gradient < self::GRADIENT_THRESHOLD ? $lowGradient : $highGradient;
		}
		return $highGradient;
	}
}
