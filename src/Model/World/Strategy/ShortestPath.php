<?php
declare(strict_types = 1);
namespace Lemuria\Model\World\Strategy;

use Lemuria\Model\Location;
use Lemuria\Model\World\Direction;

class ShortestPath extends AbstractPathStrategy
{
	protected int $d;

	protected Location $current;

	public function find(Location $from, Location $to): static {
		if ($this->isSameLocation($from, $to)) {
			return $this;
		}
		$t             = $to->Id()->Id();
		$this->current = $from;
		$distance      = $this->world->getDistance($from, $to);

		while ($distance > 1) {
			$this->d = $distance - 1;
			$ways    = [];
			$n       = 0;
			foreach ($this->path as $way) {
				$this->current = $way->last();
				$n             = 0;
				foreach ($this->world->getNeighbours($this->current) as $direction => $neighbour) {
					if ($this->isValidNeighbour($direction, $neighbour)) {
						$ways[] = [$direction, $neighbour, ++$n > 1 ? $way->clone() : $way];
					}
				}
			}
			for ($i = 0; $i < $n; $i++) {
				$next = $ways[$i];
				$way  = $next[2];
				$way[$next[0]] = $next[1];
				if ($i > 0) {
					$this->path[] = $way;
				}
			}
			$distance = $this->d;

			if (empty($ways)) {
				$this->path->clear();
				return $this;
			}
		}

		foreach ($this->path as $way) {
			$last = $way->last();
			foreach ($this->world->getNeighbours($last) as $direction => $neighbour) {
				if ($neighbour->Id()->Id() === $t) {
					$way[$direction] = $neighbour;
					break;
				}
			}
		}

		return $this;
	}

	/**
	 * @noinspection PhpUnusedParameterInspection
	 */
	protected function isValidNeighbour(Direction $direction, Location $neighbour): bool {
		return $this->world->getDistance($neighbour, $this->end) === $this->d;
	}
}
