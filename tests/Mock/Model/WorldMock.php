<?php
declare(strict_types = 1);
namespace Lemuria\Tests\Mock\Model;

use Lemuria\Exception\InvalidClassTypeException;
use Lemuria\Model\Coordinates;
use Lemuria\Model\Location;
use Lemuria\Model\Neighbours;
use Lemuria\Model\World;
use Lemuria\Model\World\Direction;
use Lemuria\Model\World\Path;
use Lemuria\Model\World\PathStrategy;

class WorldMock implements World
{
	public function serialize(): array {
		return [];
	}

	public function unserialize(array $data): static {
		return $this;
	}

	public function getCoordinates(Location $location): Coordinates {
		return new World\MapCoordinates();
	}

	public function getDistance(Location $from, Location $to): int {
		return 0;
	}

	public function getNeighbours(Location $location): Neighbours {
		return new Neighbours();
	}

	public function getPath(Location $start, Direction $direction, int $distance): Path {
		return new Path();
	}

	public function getAlternatives(Location $location, Direction $direction): Neighbours {
		return new Neighbours();
	}

	public function findPath(Location $from, Location $to, string $pathStrategy): PathStrategy {
		try {
			$strategy = new $pathStrategy($this);
			if (!($strategy instanceof PathStrategy)) {
				throw new InvalidClassTypeException($pathStrategy, PathStrategy::class);
			}
		} catch (\TypeError $e) {
			throw new InvalidClassTypeException($pathStrategy, PathStrategy::class, $e);
		}
		return $strategy->find($from, $to);
	}

	public function isDirection(Direction $direction): bool {
		return true;
	}

	public function load(): static {
		return $this;
	}

	public function save(): static {
		return $this;
	}
}
