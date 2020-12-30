<?php
declare (strict_types = 1);
namespace Lemuria\Model\World;

use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\ExpectedValues;
use JetBrains\PhpStorm\Pure;

use Lemuria\Exception\LemuriaException;
use Lemuria\Exception\UnserializeEntityException;
use Lemuria\Exception\UnserializeException;
use Lemuria\Id;
use Lemuria\Lemuria;
use Lemuria\Model\Catalog;
use Lemuria\Model\Exception\MapException;
use Lemuria\Model\Coordinates;
use Lemuria\Model\Location;
use Lemuria\Model\World;
use Lemuria\Serializable;
use Lemuria\SerializableTrait;

/**
 * Representation of a two-dimensional world.
 */
abstract class BaseMap implements World
{
	use SerializableTrait;

	/**
	 * @var string[]
	 */
	protected array $directions = [World::NORTH, World::NORTHEAST, World::EAST, World::SOUTHEAST, World::SOUTH,
								   World::SOUTHWEST, World::WEST, World::NORTHWEST];

	/**
	 * @var array[]
	 */
	protected array $map = [];

	private Coordinates $origin;

	/**
	 * @var array(int=>Coordinates)
	 */
	private array $coordinates = [];

	/**
	 * Create an empty map.
	 */
	#[Pure] public function __construct() {
		$this->origin = new MapCoordinates();
	}

	/**
	 * Get a plain data array of the model's data.
	 */
	#[ArrayShape(['origin' => 'array', 'map' => 'array'])]
	#[Pure]
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
	 * Restore the model's data from serialized data.
	 *
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
	 * @param Location $location
	 * @return Coordinates
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
	 *
	 * @param string $direction
	 * @return bool
	 */
	#[Pure] public function isDirection(#[ExpectedValues(valuesFromClass: World::class)] string $direction): bool {
		return in_array($direction, $this->directions);
	}

	/**
	 * Load the world data.
	 *
	 * @return World
	 */
	public function load(): World {
		$this->unserialize(Lemuria::Game()->getWorld());
		return $this;
	}

	/**
	 * Save the world data.
	 *
	 * @return World
	 */
	public function save(): World {
		Lemuria::Game()->setWorld($this->serialize());
		return $this;
	}

	/**
	 * Get a location.
	 *
	 * @param int|null $id
	 * @return Location|null
	 */
	protected function getLocation(?int $id): ?Location {
		if ($id) {
			$id       = new Id($id);
			$location = Lemuria::Catalog()->get($id, Catalog::LOCATIONS);
			if ($location instanceof Location) {
				return $location;
			}
			throw new LemuriaException('Invalid location ' . $id . '.');
		}
		return null;
	}

	/**
	 * Check that a serialized data array is valid.
	 *
	 * @param array(string=>mixed) $data
	 * @throws UnserializeEntityException
	 */
	private function validateSerializedData(&$data): void {
		$this->validate($data, 'origin', 'array');
		$this->validate($data, 'map', 'array');
	}
}
