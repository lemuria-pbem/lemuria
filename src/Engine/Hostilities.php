<?php
declare(strict_types = 1);
namespace Lemuria\Engine;

use Lemuria\Engine\Combat\Battle;
use Lemuria\Identifiable;
use Lemuria\Model\Location;

interface Hostilities
{
	/**
	 * Search for the battle in a location where a specific entity is engaged.
	 */
	public function find(Location $location, Identifiable $entity): ?Battle;

	/**
	 * Search for all battles in a location.
	 *
	 * @return Battle[]
	 */
	public function findAll(Location $location): array;

	/**
	 * Search for all battles where a specific entity is engaged.
	 *
	 * @return Battle[]
	 */
	public function findFor(Identifiable $entity): array;

	/**
	 * Add a Battle to persistence.
	 */
	public function add(Battle $battle): Hostilities;

	/**
	 * Delete all battles as preparation for a new turn.
	 */
	public function clear(): Hostilities;

	/**
	 * Load battles.
	 */
	public function load(): Hostilities;

	/**
	 * Save battles.
	 */
	public function save(): Hostilities;
}
