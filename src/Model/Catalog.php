<?php
declare (strict_types = 1);
namespace Lemuria\Model;

use JetBrains\PhpStorm\ExpectedValues;

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
	 */
	public function has(Id $id, #[ExpectedValues(valuesFromClass: self::class)] int $namespace): bool;

	/**
	 * Check if game data has been loaded.
	 */
	public function isLoaded(): bool;

	/**
	 * Get the specified entity.
	 *
	 * @throws NotRegisteredException
	 */
	public function get(Id $id, #[ExpectedValues(valuesFromClass: self::class)] int $namespace): Identifiable;

	/**
	 * Get all entities of a namespace.
	 */
	public function getAll(#[ExpectedValues(valuesFromClass: self::class)] int $namespace): array;

	/**
	 * Load game data into catalog.
	 */
	public function load(): Catalog;

	/**
	 * Save game data from catalog.
	 */
	public function save(): Catalog;

	/**
	 * Register an entity.
	 *
	 * @throws DuplicateIdException
	 */
	public function register(Identifiable $identifiable): Catalog;

	/**
	 * Remove an entity.
	 *
	 * @throws NotRegisteredException
	 */
	public function remove(Identifiable $identifiable): Catalog;

	/**
	 * Propagate change of an entity's ID.
	 */
	public function reassign(Id $oldId, Identifiable $identifiable): Catalog;

	/**
	 * Reserve the next ID that is available for a namespace.
	 */
	public function nextId(#[ExpectedValues(valuesFromClass: self::class)] int $namespace): Id;

	/**
	 * Register a reassignment listener.
	 *
	 * @param Reassignment $listener
	 * @return Catalog
	 */
	public function addReassignment(Reassignment $listener): Catalog;
}
