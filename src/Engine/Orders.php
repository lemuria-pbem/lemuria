<?php
declare(strict_types = 1);
namespace Lemuria\Engine;

use Lemuria\Identifiable;

interface Orders
{
	/**
	 * Get the list of current orders for an entity.
	 */
	public function getCurrent(Identifiable $entity): Instructions;

	/**
	 * Get the list of new default orders for an entity.
	 */
	public function getDefault(Identifiable $entity): Instructions;

	/**
	 * Load orders data.
	 */
	public function load(): Report;

	/**
	 * Save orders data.
	 */
	public function save(): Report;
}
