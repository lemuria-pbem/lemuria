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
	 * Add a Battle to persistence.
	 */
	public function add(Battle $battle): Hostilities;

	/**
	 * Load battles.
	 */
	public function load(): Hostilities;

	/**
	 * Save battles.
	 */
	public function save(): Hostilities;
}
