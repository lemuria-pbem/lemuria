<?php
declare(strict_types = 1);
namespace Lemuria\Model\World\Strategy;

use Lemuria\Model\Location;

class ShortestPath extends AbstractPathStrategy
{
	public function find(Location $from, Location $to): static {
		if ($this->isSameLocation($from, $to)) {
			return $this;
		}
		$t        = $to->Id()->Id();
		$distance = $this->world->getDistance($from, $to);

		while ($distance > 1) {
			$d    = $distance - 1;
			$ways = [];
			$n    = 0;
			foreach ($this->path as $way) {
				$from = $way->last();
				foreach ($this->world->getNeighbours($from) as $direction => $neighbour) {
					if ($this->world->getDistance($neighbour, $to) === $d) {
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
			$distance = $d;
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
}
