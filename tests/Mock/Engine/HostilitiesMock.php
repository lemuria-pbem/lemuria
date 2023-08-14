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

	public function add(Battle $battle): static {
		return $this;
	}

	public function clear(): static {
		return $this;
	}

	public function load(): static {
		return $this;
	}

	public function save(): static {
		return $this;
	}
}
