<?php
declare(strict_types = 1);
namespace Lemuria\Model\World;

use Lemuria\Model\Location;

interface Map
{
	public function Geometry(): Geometry;

	public function Width(): int;

	public function Height(): int;

	public function isEdge(Location $location): bool;

	public function getBeyond(Location $location): Beyond;
}
