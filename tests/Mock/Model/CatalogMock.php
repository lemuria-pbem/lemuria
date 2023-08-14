<?php
declare(strict_types = 1);
namespace Lemuria\Tests\Mock\Model;

use Lemuria\Id;
use Lemuria\Identifiable;
use Lemuria\Model\Catalog;
use Lemuria\Model\Domain;
use Lemuria\Model\Exception\NotRegisteredException;
use Lemuria\Model\Reassignment;
use Lemuria\Version\VersionFinder;
use Lemuria\Version\VersionTag;

class CatalogMock implements Catalog
{
	public function has(Id $id, Domain $domain): bool {
		return false;
	}

	public function isLoaded(): bool {
		return true;
	}

	/**
	 * @throws NotRegisteredException
	 */
	public function get(Id $id, Domain $domain): Identifiable {
		throw new NotRegisteredException($id);
	}

	public function getAll(Domain $domain): array {
		return [];
	}

	public function load(): static {
		return $this;
	}

	public function save(): static {
		return $this;
	}

	public function register(Identifiable $identifiable): static {
		return $this;
	}

	public function remove(Identifiable $identifiable): static {
		return $this;
	}

	public function reassign(Identifiable $identifiable, ?Id $oldId = null): static {
		return $this;
	}

	public function nextId(Domain $domain): Id {
		return new Id(1);
	}

	public function addReassignment(Reassignment $listener): static {
		return $this;
	}

	public function getVersion(): VersionTag {
		$versionFinder = new VersionFinder(__DIR__ . '/../../..');
		return $versionFinder->get();
	}
}
