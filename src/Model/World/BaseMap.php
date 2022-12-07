<?php
declare (strict_types = 1);
namespace Lemuria\Model\World;

use Lemuria\Exception\LemuriaException;
use Lemuria\Exception\UnserializeEntityException;
use Lemuria\Exception\UnserializeException;
use Lemuria\Id;
use Lemuria\Lemuria;
use Lemuria\Model\Domain;
use Lemuria\Model\Exception\MapException;
use Lemuria\Model\Coordinates;
use Lemuria\Model\Location;
use Lemuria\Model\Neighbours;
use Lemuria\Model\World;
use Lemuria\Serializable;
use Lemuria\SerializableTrait;
use Lemuria\Validate;

/**
 * Representation of a two-dimensional world.
 */
abstract class BaseMap implements World
{
	use SerializableTrait;

	protected final const ORIGIN = 'origin';

	protected final const MAP = 'map';

	/**
	 * @var string[]
	 */
	protected array $directions = [Direction::NORTH, Direction::NORTHEAST, Direction::EAST, Direction::SOUTHEAST,
		                           Direction::SOUTH, Direction::SOUTHWEST, Direction::WEST, Direction::NORTHWEST];

	/**
	 * @var array[]
	 */
	protected array $map = [];

	protected Coordinates $origin;

	/**
	 * @var array<int, Coordinates>
	 */
	protected array $coordinates = [];

	/**
	 * Create an empty map.
	 */
	public function __construct() {
		$this->origin = new MapCoordinates();
	}

	/**
	 * @return array<string, array>
	 */
	public function serialize(): array {
		$map = [];
		foreach ($this->map as $ids) {
			$regions = [];
			foreach ($ids as $id) {
				$regions[] = $id;
			}
			$map[] = $regions;
		}

		return [
			'origin' => $this->origin->serialize(),
			'map'    => $map
		];
	}

	/**
	 * @param array<string, array> $data
	 * @throws UnserializeException
	 */
	public function unserialize(array $data): Serializable {
		$this->validateSerializedData($data);
		$this->origin->unserialize($data['origin']);
		$y = $this->origin->Y();
		foreach ($data['map'] as $map) {
			$regions = [];
			$x       = $this->origin->X();
			if (!is_array($map)) {
				throw new UnserializeException('Map must be an array of arrays.');
			}
			foreach ($map as $id) {
				if ($id === null) {
					$regions[$x++] = null;
				} else {
					if (!is_int($id)) {
						throw new UnserializeException('Map must contain only integer IDs.');
					}
					$regions[$x]            = $id;
					$this->coordinates[$id] = new MapCoordinates($x++, $y);
				}
			}
			$this->map[$y++] = $regions;
		}
		return $this;
	}

	/**
	 * Get the world coordinates of a location.
	 *
	 * @throws MapException
	 */
	public function getCoordinates(Location $location): Coordinates {
		$id = $location->Id()->Id();
		if (!isset($this->coordinates[$id])) {
			throw new MapException($location);
		}
		return $this->coordinates[$id];
	}

	/**
	 * Check if a direction is valid in this world.
	 */
	public function isDirection(Direction $direction): bool {
		return in_array($direction, $this->directions);
	}

	/**
	 * Load the world data.
	 */
	public function load(): World {
		$this->unserialize(Lemuria::Game()->getWorld());
		return $this;
	}

	/**
	 * Save the world data.
	 */
	public function save(): World {
		Lemuria::Game()->setWorld($this->serialize());
		return $this;
	}

	protected function getLocation(?int $id): ?Location {
		if ($id) {
			$id       = new Id($id);
			$location = Lemuria::Catalog()->get($id, Domain::LOCATION);
			if ($location instanceof Location) {
				return $location;
			}
			throw new LemuriaException('Invalid location ' . $id . '.');
		}
		return null;
	}

	protected function setNeighbour(Direction $direction, int $y, int $x, Neighbours $neighbours): void {
		$location = $this->getByCoordinates($y, $x);
		if ($location) {
			$neighbours[$direction] = $location;
		}
	}

	protected function getByCoordinates(int $y, int $x): ?Location {
		return $this->getLocation($this->map[$y][$x] ?? null);
	}

	/**
	 * Check that a serialized data array is valid.
	 *
	 * @param array<string, array> $data
	 * @throws UnserializeEntityException
	 */
	private function validateSerializedData($data): void {
		$this->validate($data, 'origin', Validate::Array);
		$this->validate($data, 'map', Validate::Array);
	}
}
