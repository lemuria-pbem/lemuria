<?php
declare(strict_types = 1);
namespace Lemuria\Model\World\Island;

use Lemuria\Model\Coordinates;
use Lemuria\Model\Location;

interface Locator
{
	public function findNeighbour(Location $location, Coordinates $coordinates, Island $island): ?Location;

	public function hasIntersection(Island $island, Island $other): bool;

	public function hasNeighbour(Island $island, Island $other): bool;
}
