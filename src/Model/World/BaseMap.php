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
use Lemuria\SerializableTrait;
use Lemuria\Validate;

/**
 * Representation of a two-dimensional world.
 */
abstract class BaseMap implements Map, World
{
	use SerializableTrait;

	private const string ORIGIN = 'origin';

	private const string GEOMETRY = 'geometry';

	private const string MAP = 'map';

	/**
	 * @var array<string>
	 */
	protected array $directions = [Direction::North, Direction::Northeast, Direction::East, Direction::Southeast,
		                           Direction::South, Direction::Southwest, Direction::West, Direction::Northwest];

	/**
	 * @var array<int, array<int, int>>
	 */
	protected array $map = [];

	protected Coordinates $origin;

	protected Geometry $geometry = Geometry::Flat;

	/**
	 * @var array<int, Coordinates>
	 */
	protected array $coordinates = [];

	protected int $width = 0;

	protected int $height = 0;

	/**
	 * Create an empty map.
	 */
	public function __construct() {
		$this->origin = new MapCoordinates();
	}

	public function Geometry(): Geometry {
		return $this->geometry;
	}

	public function Width(): int {
		return $this->width;
	}

	public function Height(): int {
		return $this->height;
	}

	public function isEdge(Location $location): bool {
		$coordinates = $this->getCoordinates($location);

		$x  = $coordinates->X();
		$x0 = $this->origin->X();
		if ($x === $x0 || $x === $x0 + $this->width - 1) {
			return true;
		}

		$y  = $coordinates->Y();
		$y0 = $this->origin->Y();
		if ($y === $y0 || $y === $y0 + $this->height - 1) {
			return true;
		}

		return false;
	}

	public function getBeyond(Location $location): Beyond {
		$beyond = new Beyond($this->origin);
		if ($this->isEdge($location)) {
			$coordinates = $this->getNeighbourCoordinates($location);
			foreach ($coordinates as $coordinate) {
				$x = $coordinate->X();
				$y = $coordinate->Y();
				if (!$this->getByCoordinates($y, $x)) {
					$beyond->add($coordinate, $this->getBySphericalCoordinates($y, $x));
				}
			}
		}
		return $beyond;
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
			self::ORIGIN   => $this->origin->serialize(),
			self::GEOMETRY => $this->geometry->value,
			self::MAP      => $map,
		];
	}

	/**
	 * @param array<string, array> $data
	 * @throws UnserializeException
	 */
	public function unserialize(array $data): static {
		$this->validateSerializedData($data);
		$this->origin->unserialize($data[self::ORIGIN]);
		try {
			$this->geometry = Geometry::from($data[self::GEOMETRY]);
		} catch (\ValueError $e) {
			throw new UnserializeException('Map geometry is invalid.', previous: $e);
		}
		$y = $this->origin->Y();
		foreach ($data[self::MAP] as $map) {
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
			$this->width     = max($this->width, count($map));
		}
		$this->height = count($data[self::MAP]);
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
	 * Get the neighbour regions of a location.
	 */
	public function getNeighbours(Location $location): Neighbours {
		$neighbours = new Neighbours();
		foreach ($this->getNeighbourCoordinates($location) as $direction => $coordinate) {
			$this->setNeighbour(Direction::from($direction), $coordinate, $neighbours);
		}
		return $neighbours;
	}

	/**
	 * Get the neighbours of a region in alternative directions.
	 */
	public function getAlternatives(Location $location, Direction $direction): Neighbours {
		$neighbours = new Neighbours();
		foreach ($this->getAlternativeCoordinates($location, $direction) as $direction => $coordinate) {
			$this->setNeighbour(Direction::from($direction), $coordinate, $neighbours);
		}
		return $neighbours;
	}

	/**
	 * Find a path between two locations.
	 */
	public function findPath(Location $from, Location $to, PathStrategy $strategy): PathStrategy {
		return $strategy->find($from, $to);
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
	public function load(): static {
		$this->unserialize(Lemuria::Game()->getWorld());
		return $this;
	}

	/**
	 * Save the world data.
	 */
	public function save(): static {
		Lemuria::Game()->setWorld($this->serialize());
		return $this;
	}

	/**
	 * @return array<string, Coordinates>
	 */
	abstract protected function getNeighbourCoordinates(Location $location): array;

	/**
	 * @return array<string, Coordinates>
	 */
	abstract protected function getAlternativeCoordinates(Location $location, Direction $direction): array;

	protected function getLocation(?int $id): ?Location {
		if ($id) {
			$id       = new Id($id);
			$location = Lemuria::Catalog()->get($id, Domain::Location);
			if ($location instanceof Location) {
				return $location;
			}
			throw new LemuriaException('Invalid location ' . $id . '.');
		}
		return null;
	}

	protected function setNeighbour(Direction $direction, Coordinates $coordinates, Neighbours $neighbours): void {
		$x        = $coordinates->X();
		$y        = $coordinates->Y();
		$location = $this->getByCoordinates($y, $x);
		if ($location) {
			$neighbours[$direction] = $location;
		} elseif ($this->geometry === Geometry::Spherical) {
			$location = $this->getBySphericalCoordinates($y, $x);
			if ($location) {
				$neighbours[$direction] = $location;
			}
		}
	}

	protected function getByCoordinates(int $y, int $x): ?Location {
		return $this->getLocation($this->map[$y][$x] ?? null);
	}

	protected function getBySphericalCoordinates(int $y, int $x): ?Location {
		$y0 = $this->origin->Y();
		$y1 = $y0 + $this->height - 1;
		if ($y < $y0) {
			$y = $y1;
		} elseif ($y > $y1) {
			$y = $y0;
		}
		$x0 = $this->origin->X();
		$x1 = $x0 + $this->width - 1;
		if ($x < $x0) {
			$x = $x1;
		} elseif ($x > $x1) {
			$x = $x0;
		}
		return $this->getByCoordinates($y, $x);
	}

	/**
	 * Check that a serialized data array is valid.
	 *
	 * @param array<string, array> $data
	 * @throws UnserializeEntityException
	 */
	private function validateSerializedData($data): void {
		$this->validate($data, self::ORIGIN, Validate::Array);
		$this->validateEnum($data, self::GEOMETRY, Geometry::class);
		$this->validate($data, self::MAP, Validate::Array);
	}
}
