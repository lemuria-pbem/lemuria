<?php
declare(strict_types = 1);
namespace Lemuria\Tests\Mock\Engine;

use Lemuria\Engine\Combat\Battle;
use Lemuria\Engine\Hostilities;
use Lemuria\Identifiable;
use Lemuria\Model\Location;

class HostilitiesMock implements Hostilities
{
	public function find(Location $location, Identifiable $entity): ?Battle {
		return null;
	}

	public function findAll(Location $location): array {
		return [];
	}

	public function findFor(Identifiable $entity): array {
		return [];
	}

	public function add(Battle $battle): Hostilities {
		return $this;
	}

	public function clear(): Hostilities {
		return $this;
	}

	public function load(): Hostilities {
		return $this;
	}

	public function save(): Hostilities {
		return $this;
	}
}
