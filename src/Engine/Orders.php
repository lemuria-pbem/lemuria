<?php
declare(strict_types = 1);
namespace Lemuria\Engine;

use Lemuria\Id;

interface Orders
{
	/**
	 * Get the list of current orders for an entity.
	 */
	public function getCurrent(Id $id): Instructions;

	/**
	 * Get the list of new default orders for an entity.
	 */
	public function getDefault(Id $id): Instructions;

	/**
	 * Load orders data.
	 */
	public function load(): static;

	/**
	 * Save orders data.
	 */
	public function save(): static;

	/**
	 * Clear all orders in preparation for a new turn.
	 */
	public function clear(): static;
}
