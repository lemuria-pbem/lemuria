<?php
declare (strict_types = 1);
namespace Lemuria\Model\World;

use Lemuria\Exception\LemuriaException;
use Lemuria\Model\Coordinates;
use Lemuria\Model\Location;

/**
 * Representation of a two-dimensional world with eight directions.
 */
final class OctagonalMap extends BaseMap
{
	private const array DIRECTION_ANGLE = [
		Direction::None->value      => NAN,
		Direction::North->value     => M_PI_2,
		Direction::Northeast->value => M_PI_4,
		Direction::East->value      => 0.0,
		Direction::Southeast->value => -M_PI_4,
		Direction::South->value     => -M_PI_2,
		Direction::Southwest->value => -M_PI_2 - M_PI_4,
		Direction::West->value      => M_PI,
		Direction::Northwest->value => M_PI_2 + M_PI_4
	];

	private const float GRADIENT_THRESHOLD_1 = 0.57735026919;

	private const float GRADIENT_THRESHOLD_2 = 1.73205080757;

	/**
	 * Get the shortest distance between two regions.
	 */
	public function getDistance(Location $from, Location $to): int {
		$fromCoordinates = $this->getCoordinates($from);
		$toCoordinates   = $this->getCoordinates($to);
		$dx              = abs($toCoordinates->X() - $fromCoordinates->X());
		$dy              = abs($toCoordinates->Y() - $fromCoordinates->Y());

		if ($this->geometry === Geometry::Spherical) {
			$half = (int)ceil($this->width / 2);
			if ($dx > $half) {
				$dx = $this->width - $dx;
			}
			$half = (int)ceil($this->height / 2);
			if ($dy > $half) {
				$dy = $this->height - $dy;
			}
		}

		return max($dx, $dy);
	}

	/**
	 * Get the path from a location to a distant point.
	 */
	public function getPath(Location $start, Direction $direction, int $distance): Path {
		return match ($direction) {
			Direction::North     => $this->createWay($start, 1, 0, $distance, $direction),
			Direction::Northeast => $this->createWays($start, 1, 1, $distance, $direction),
			Direction::East      => $this->createWay($start, 0, 1, $distance, $direction),
			Direction::Southeast => $this->createWays($start, -1, 1, $distance, $direction),
			Direction::South     => $this->createWay($start, -1, 0, $distance, $direction),
			Direction::Southwest => $this->createWays($start, -1, -1, $distance, $direction),
			Direction::West      => $this->createWay($start, 0, -1, $distance, $direction),
			Direction::Northwest => $this->createWays($start, 1, -1, $distance, $direction),
			default              => throw new LemuriaException('Direction ' . $direction->value . ' is not supported.')
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
			Direction::North->value     => new MapCoordinates($x, ++$y),
			Direction::Northeast->value => new MapCoordinates(++$x, $y),
			Direction::East->value      => new MapCoordinates($x, --$y),
			Direction::Southeast->value => new MapCoordinates($x, --$y),
			Direction::South->value     => new MapCoordinates(--$x, $y),
			Direction::Southwest->value => new MapCoordinates(--$x, $y),
			Direction::West->value      => new MapCoordinates($x, ++$y),
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
			Direction::North => [
				Direction::Northwest->value => new MapCoordinates($x - 1, $y + 1),
				Direction::Northeast->value => new MapCoordinates($x + 1, $y + 1),
				Direction::West->value      => new MapCoordinates($x - 1, $y),
				Direction::East->value      => new MapCoordinates($x + 1, $y)
			],
			Direction::Northeast => [
				Direction::North->value     => new MapCoordinates($x, $y + 1),
				Direction::East->value      => new MapCoordinates($x + 1, $y),
				Direction::Northwest->value => new MapCoordinates($x - 1, $y + 1),
				Direction::Southeast->value => new MapCoordinates($x + 1, $y - 1)
			],
			Direction::East => [
				Direction::Northeast->value => new MapCoordinates($x + 1, $y + 1),
				Direction::Southeast->value => new MapCoordinates($x + 1, $y - 1),
				Direction::North->value     => new MapCoordinates($x, $y + 1),
				Direction::South->value     => new MapCoordinates($x, $y - 1)
			],
			Direction::Southeast => [
				Direction::East->value      => new MapCoordinates($x + 1, $y),
				Direction::South->value     => new MapCoordinates($x, $y - 1),
				Direction::Northeast->value => new MapCoordinates($x + 1, $y + 1),
				Direction::Southwest->value => new MapCoordinates($x - 1, $y - 1)
			],
			Direction::South => [
				Direction::Southeast->value => new MapCoordinates($x + 1, $y - 1),
				Direction::Southwest->value => new MapCoordinates($x - 1, $y - 1),
				Direction::East->value      => new MapCoordinates($x + 1, $y),
				Direction::West->value      => new MapCoordinates($x - 1, $y)
			],
			Direction::Southwest => [
				Direction::South->value     => new MapCoordinates($x, $y - 1),
				Direction::West->value      => new MapCoordinates($x - 1, $y),
				Direction::Southeast->value => new MapCoordinates($x + 1, $y - 1),
				Direction::Northwest->value => new MapCoordinates($x - 1, $y + 1)
			],
			Direction::West => [
				Direction::Southwest->value => new MapCoordinates($x - 1, $y - 1),
				Direction::Northwest->value => new MapCoordinates($x - 1, $y + 1),
				Direction::South->value     => new MapCoordinates($x, $y - 1),
				Direction::North->value     => new MapCoordinates($x, $y + 1)
			],
			Direction::Northwest => [
				Direction::West->value      => new MapCoordinates($x - 1, $y),
				Direction::North->value     => new MapCoordinates($x, $y + 1),
				Direction::Southwest->value => new MapCoordinates($x - 1, $y - 1),
				Direction::Northeast->value => new MapCoordinates($x + 1, $y + 1)
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
				return $this->calculateDirectionByGradient($x, $y, Direction::East, Direction::Northeast, Direction::North);
			}
			return $this->calculateDirectionByGradient(-$x, $y, Direction::West, Direction::Northwest, Direction::North);
		}
		if ($x >= 0) {
			return $this->calculateDirectionByGradient($x, -$y, Direction::East, Direction::Southeast, Direction::South);
		}
		return $this->calculateDirectionByGradient(-$x, -$y, Direction::West, Direction::Southwest, Direction::South);
	}

	private function createWay(Location $location, int $dY, int $dX, int $distance, Direction $direction): Path {
		$path = new Path();
		$way  = new Way();
		$way->offsetSet(Direction::None, $location);
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
			$way->offsetSet($direction, $location);
		}
		$path[0] = $way;
		return $path;
	}

	private function createWays(Location $location, int $dY, int $dX, int $distance, Direction $direction): Path {
		$path        = new Path();
		$i           = 0;
		$coordinates = $this->getCoordinates($location);
		$x           = $coordinates->X();
		$y           = $coordinates->Y();
		$nY          = 1;
		do {
			$way = new Way();
			$way->offsetSet(Direction::None, $location);
			$nX = 1;
			while (true) {
				$d = (int)round(sqrt($nX ** 2 + $nY ** 2));
				if ($d > $distance) {
					break;
				}
				$next = $this->getByCoordinates($y + $nY * $dY, $x + $nX * $dX);
				if ($next) {
					$way->offsetSet($direction, $next);
					$nX++;
				} else {
					break;
				}
			}
			if ($way->count() >= 2) {
				$path[$i++] = $way;
			}
			$nY++;
		} while ($nY <= $distance);
		return $path;
	}

	private function calculateDirectionByGradient(float $x, float $y, Direction $lowGradient, Direction $mediumGradient, Direction $highGradient): Direction {
		if ($x > 0) {
			$gradient = $y / $x;
			return $gradient < self::GRADIENT_THRESHOLD_1 ? $lowGradient : ($gradient < self::GRADIENT_THRESHOLD_2 ? $mediumGradient : $highGradient);
		}
		return $highGradient;
	}
}
