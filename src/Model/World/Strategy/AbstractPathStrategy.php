<?php
declare(strict_types = 1);
namespace Lemuria\Model\World\Strategy;

use Lemuria\Exception\LemuriaException;
use Lemuria\Model\Location;
use Lemuria\Model\World;
use Lemuria\Model\World\Direction;
use Lemuria\Model\World\Path;
use Lemuria\Model\World\PathStrategy;
use Lemuria\Model\World\Way;

abstract class AbstractPathStrategy implements PathStrategy
{
	protected Path $path;

	protected Location $start;

	protected Location $end;

	public function __construct(protected World $world) {
		$this->path = new Path();
	}

	public function isViable(): bool {
		return $this->path->count() > 0;
	}

	public function getAll(): Path {
		return $this->path;
	}

	public function getBest(): Way {
		if ($this->path->count() > 0) {
			return $this->path[0];
		}
		throw new LemuriaException('This strategy has not found a way.');
	}

	protected function setStartEnd(Location $start, Location $end): void {
		$this->start = $start;
		$this->end   = $end;
	}

	protected function isSameLocation(Location $from, Location $to): bool {
		$this->setStartEnd($from, $to);
		$way = new Way();
		$way->offsetSet(Direction::None, $from);
		$this->path->offsetSet(0, $way);
		return $from->Id()->Id() === $to->Id()->Id();
	}
}
