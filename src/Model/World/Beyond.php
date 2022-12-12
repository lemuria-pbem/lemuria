<?php
declare(strict_types = 1);
namespace Lemuria\Model\World;

use Lemuria\Exception\LemuriaException;
use Lemuria\Model\Coordinates;
use Lemuria\Model\Location;

class Beyond implements \Countable
{
	protected Coordinates $offset;

	/**
	 * @var array<Coordinates>
	 */
	protected array $coordinates = [];

	/**
	 * @var array<Location>
	 */
	protected array $locations = [];

	public function __construct(protected readonly Coordinates $origin) {
		$this->offset = $this->origin;
	}

	public function count(): int {
		return count($this->coordinates);
	}

	public function getCoordinates(int $index): Coordinates {
		$coordinates = $this->coordinates[$index] ?? null;
		if (!$coordinates) {
			throw new LemuriaException();
		}
		return new MapCoordinates($coordinates->X() - $this->offset->X(), $coordinates->Y() - $this->offset->Y());
	}

	public function getLocation(int $index): Location {
		if (!isset($this->locations[$index])) {
			throw new LemuriaException();
		}
		return $this->locations[$index];
	}

	public function setOffset(Coordinates $coordinates): Beyond {
		$this->offset = $coordinates;
		return $this;
	}

	public function add(Coordinates $coordinates, Location $location): Beyond {
		$this->coordinates[] = $coordinates;
		$this->locations[]   = $location;
		return $this;
	}
}
