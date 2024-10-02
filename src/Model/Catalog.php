<?php
declare (strict_types = 1);
namespace Lemuria\Model;

use Lemuria\Dispatcher\Attribute\Emit;
use Lemuria\Dispatcher\Event\Catalog\Loaded;
use Lemuria\Dispatcher\Event\Catalog\Saved;
use Lemuria\Id;
use Lemuria\Identifiable;
use Lemuria\Model\Exception\DuplicateIdException;
use Lemuria\Model\Exception\NotRegisteredException;
use Lemuria\Version\VersionTag;

/**
 * The catalog registers all entities and is used to ensure that IDs are only used once per namespace.
 */
interface Catalog
{
	/**
	 * Checks if an entity exists in the specified catalog domain.
	 */
	public function has(Id $id, Domain $domain): bool;

	/**
	 * Check if game data has been loaded.
	 */
	public function isLoaded(): bool;

	/**
	 * Get the specified entity.
	 *
	 * @throws NotRegisteredException
	 */
	public function get(Id $id, Domain $domain): Identifiable;

	/**
	 * Get all entities of a domain.
	 */
	public function getAll(Domain $domain): array;

	/**
	 * Load game data into catalog.
	 */
	#[Emit(Loaded::class)]
	public function load(): static;

	/**
	 * Save game data from catalog.
	 */
	#[Emit(Saved::class)]
	public function save(): static;

	/**
	 * Register an entity.
	 *
	 * @throws DuplicateIdException
	 */
	public function register(Identifiable $identifiable): static;

	/**
	 * Remove an entity.
	 *
	 * @throws NotRegisteredException
	 */
	public function remove(Identifiable $identifiable): static;

	/**
	 * Propagate change of an entity's ID.
	 *
	 * If old ID is null, propagate removal instead of reassignment.
	 */
	public function reassign(Identifiable $identifiable, ?Id $oldId = null): static;

	/**
	 * Reserve the next ID that is available for a domain.
	 */
	public function nextId(Domain $domain): Id;

	/**
	 * Register a reassignment listener.
	 */
	public function addReassignment(Reassignment $listener): static;

	/**
	 * Get the version of the model package of this catalog.
	 */
	public function getVersion(): VersionTag;
}
