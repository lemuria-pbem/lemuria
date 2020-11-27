<?php
declare (strict_types = 1);
namespace Lemuria\Model\World;

use Lemuria\Entity;
use Lemuria\EntitySet;
use Lemuria\Exception\LemuriaException;
use Lemuria\Id;
use Lemuria\Lemuria;
use Lemuria\Model\Catalog;
use Lemuria\Model\Location;
use Lemuria\Sorting\ById;
use Lemuria\Sorting\Location\North2South;

/**
 * An atlas is an ordered list of locations.
 */
class Atlas extends EntitySet
{
	/**
	 * Sort mode by location ID.
	 */
	public const BY_ID = 0;

	/**
	 * Sort mode by coordinates from north to south.
	 */
	public const NORTH_TO_SOUTH = 1;

	public function add(Location $location): self {
		parent::addEntity($location->Id());
		return $this;
	}

	public function remove(Location $location): Atlas {
		parent::removeEntity($location->Id());
		return $this;
	}

	/**
	 * Sort the locations.
	 */
	public function sort(int $mode = self::BY_ID): Atlas {
		switch ($mode) {
			case self::BY_ID :
				$this->sortUsing(new ById());
				break;
			case self::NORTH_TO_SOUTH :
				$this->sortUsing(new North2South());
				break;
			default :
				throw new LemuriaException('Invalid sort mode: ' . $mode . '.');
		}
		return $this;
	}

	/**
	 * Get an Entity by ID.
	 */
	protected function get(Id $id): Entity {
		$location = Lemuria::Catalog()->get($id, Catalog::LOCATIONS);
		if ($location instanceof Entity) {
			return $location;
		}
		throw new LemuriaException('Location ' . $id . ' is not an Entity.');
	}
}
