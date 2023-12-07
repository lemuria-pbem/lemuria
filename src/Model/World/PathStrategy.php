<?php
declare(strict_types = 1);
namespace Lemuria\Model\World;

use Lemuria\Model\Location;
use Lemuria\Model\World;

interface PathStrategy
{
	public function __construct(World $world);

	public function find(Location $from, Location $to): static;

	public function getAll(): Path;

	public function getBest(): Way;
}
