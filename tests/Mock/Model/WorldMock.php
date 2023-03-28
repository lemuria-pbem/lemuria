<?php
declare(strict_types = 1);
namespace Lemuria\Tests\Mock\Model;

use Lemuria\Model\Coordinates;
use Lemuria\Model\Location;
use Lemuria\Model\Neighbours;
use Lemuria\Model\World;
use Lemuria\Model\World\Direction;
use Lemuria\Model\World\Path;
use Lemuria\Serializable;

class WorldMock implements World
{
	public function serialize(): array {
		return [];
	}

	public function unserialize(array $data): Serializable {
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

	public function isDirection(Direction $direction): bool {
		return true;
	}

	public function load(): World {
		return $this;
	}

	public function save(): World {
		return $this;
	}
}
