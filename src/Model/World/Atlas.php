<?php
declare (strict_types = 1);
namespace Lemuria\Model\World;

use Lemuria\EntitySet;
use Lemuria\Exception\LemuriaException;
use Lemuria\Id;
use Lemuria\Lemuria;
use Lemuria\Model\Domain;
use Lemuria\Model\Location;
use Lemuria\Sorting\ById;
use Lemuria\Sorting\ByName;
use Lemuria\Sorting\Location\North2South;
use Lemuria\SortMode;

/**
 * An atlas is an ordered list of locations.
 *
 * @method Location offsetGet(int|Id $offset)
 * @method Location current()
 */
class Atlas extends EntitySet
{
	public function add(Location $location): static {
		parent::addEntity($location->Id());
		return $this;
	}

	public function remove(Location $location): static {
		parent::removeEntity($location->Id());
		return $this;
	}

	/**
	 * Sort the locations.
	 */
	public function sort(SortMode $mode = SortMode::ById): static {
		switch ($mode) {
			case SortMode::Alphabetically :
				$this->sortUsing(new ByName());
				break;
			case SortMode::ById :
				$this->sortUsing(new ById());
				break;
			case SortMode::NorthToSouth :
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
	protected function get(Id $id): Location {
		return Lemuria::Catalog()->get($id, Domain::Location);
	}
}
