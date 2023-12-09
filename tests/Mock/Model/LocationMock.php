<?php
declare(strict_types = 1);
namespace Lemuria\Tests\Mock\Model;

use Lemuria\Id;
use Lemuria\Model\Domain;
use Lemuria\Model\Location;

class LocationMock implements Location
{
	protected Id $id;

	public function __construct(int $id) {
		$this->id = new Id($id);
	}

	#[\Override] public function Id(): Id {
		return $this->id;
	}

	#[\Override] public function Catalog(): Domain {
		return Domain::Location;
	}

	#[\Override] public function setId(Id $id): static {
		$this->id = $id;
		return $this;
	}
}
