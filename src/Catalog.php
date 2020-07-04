<?php
declare (strict_types = 1);
namespace Lemuria;

use Lemuria\Exception\DuplicateIdException;
use Lemuria\Exception\NotRegisteredException;

/**
 * The catalog registers all entities and is used to ensure that IDs are only used once per namespace.
 */
interface Catalog
{
	public const PARTIES = 100;

	public const UNITS = 200;

	public const REGIONS = 300;

	public const CONSTRUCTIONS = 400;

	public const VESSELS = 500;

	/**
	 * Checks if an entity exists in the specified catalog namespace.
	 *
	 * @param Id $id
	 * @param int $namespace
	 * @return bool
	 */
	public function has(Id $id, int $namespace): bool;

	/**
	 * Check if game data has been loaded.
	 *
	 * @return bool
	 */
	public function isLoaded(): bool;

	/**
	 * Get the specified entity.
	 *
	 * @param Id $id
	 * @param int $namespace
	 * @return Identifiable
	 * @throws NotRegisteredException
	 */
	public function get(Id $id, int $namespace): Identifiable;

	/**
	 * Get all entities of a namespace.
	 *
	 * @param int $namespace
	 * @return array
	 */
	public function getAll(int $namespace): array;

	/**
	 * Load game data into catalog.
	 *
	 * @return self
	 */
	public function load(): self;

	/**
	 * Save game data from catalog.
	 *
	 * @return self
	 */
	public function save(): self;

	/**
	 * Register an entity.
	 *
	 * @param Identifiable $identifiable
	 * @return self
	 * @throws DuplicateIdException
	 */
	public function register(Identifiable $identifiable): self;

	/**
	 * Remove an entity.
	 *
	 * @param Identifiable $identifiable
	 * @return self
	 * @throws NotRegisteredException
	 */
	public function remove(Identifiable $identifiable): self;

	/**
	 * Reserve the next ID that is available for a namespace.
	 *
	 * @param int $namespace
	 * @return Id
	 */
	public function nextId(int $namespace): Id;
}
