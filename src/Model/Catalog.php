<?php
declare (strict_types = 1);
namespace Lemuria\Model;

use Lemuria\Id;
use Lemuria\Identifiable;
use Lemuria\Model\Exception\DuplicateIdException;
use Lemuria\Model\Exception\NotRegisteredException;

/**
 * The catalog registers all entities and is used to ensure that IDs are only used once per namespace.
 */
interface Catalog
{
	public const PARTIES = 100;

	public const UNITS = 200;

	public const LOCATIONS = 300;

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
	 * @return Catalog
	 */
	public function load(): Catalog;

	/**
	 * Save game data from catalog.
	 *
	 * @return Catalog
	 */
	public function save(): Catalog;

	/**
	 * Register an entity.
	 *
	 * @param Identifiable $identifiable
	 * @return Catalog
	 * @throws DuplicateIdException
	 */
	public function register(Identifiable $identifiable): Catalog;

	/**
	 * Remove an entity.
	 *
	 * @param Identifiable $identifiable
	 * @return Catalog
	 * @throws NotRegisteredException
	 */
	public function remove(Identifiable $identifiable): Catalog;

	/**
	 * Reserve the next ID that is available for a namespace.
	 *
	 * @param int $namespace
	 * @return Id
	 */
	public function nextId(int $namespace): Id;
}
