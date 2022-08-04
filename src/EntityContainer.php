<?php
declare(strict_types = 1);
namespace Lemuria;

interface EntityContainer
{
	/**
	 * Check if an entity belongs to the set.
	 */
	public function contains(Identifiable $identifiable): bool;

	/**
	 * Check if an ID belongs to the set.
	 */
	public function has(Id $id): bool;
}
