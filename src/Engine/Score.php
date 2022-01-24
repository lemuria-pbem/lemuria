<?php
declare(strict_types = 1);
namespace Lemuria\Engine;

use Lemuria\Identifiable;

interface Score extends \Iterator
{
	/**
	 * Search for an existing Effect.
	 */
	public function find(Identifiable $effect): ?Identifiable;

	/**
	 * @return Identifiable[]
	 */
	public function findAll(Identifiable $entity): array;

	/**
	 * Add an Effect to persistence.
	 */
	public function add(Identifiable $effect): Score;

	/**
	 * Remove an Effect from persistence.
	 */
	public function remove(Identifiable $effect): Score;

	/**
	 * Load message data into score.
	 */
	public function load(): Score;

	/**
	 * Save message data from score.
	 */
	public function save(): Score;
}
