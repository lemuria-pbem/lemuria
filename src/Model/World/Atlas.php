<?php
declare (strict_types = 1);
namespace Lemuria\Model\World;

use Lemuria\Entity;
use Lemuria\EntitySet;
use Lemuria\Exception\LemuriaException;
use Lemuria\Id;
use Lemuria\Lemuria;
use Lemuria\Model\Domain;
use Lemuria\Model\Location;
use Lemuria\Sorting\ById;
use Lemuria\Sorting\Location\North2South;

/**
 * An atlas is an ordered list of locations.
 */
class Atlas extends EntitySet
{
	public function add(Location $location): self {
		parent::addEntity($location->Id());
		return $this;
	}

	public function remove(Location $location): self {
		parent::removeEntity($location->Id());
		return $this;
	}

	/**
	 * Sort the locations.
	 */
	public function sort(SortMode $mode = SortMode::BY_ID): self {
		switch ($mode) {
			case SortMode::BY_ID :
				$this->sortUsing(new ById());
				break;
			case SortMode::NORTH_TO_SOUTH :
				$this->sortUsing(new North2South());
				break;
			default :
				throw new LemuriaException('Unsupported sort mode: ' . $mode->name);
		}
		return $this;
	}

	/**
	 * Get an Entity by ID.
	 */
	protected function get(Id $id): Entity {
		$location = Lemuria::Catalog()->get($id, Domain::LOCATION);
		if ($location instanceof Entity) {
			return $location;
		}
		throw new LemuriaException('Location ' . $id . ' is not an Entity.');
	}
}
