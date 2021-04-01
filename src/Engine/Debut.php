<?php
declare(strict_types = 1);
namespace Lemuria\Engine;

/**
 * A catalog for newcomers.
 */
interface Debut
{
	/**
	 * Get a Newcomer.
	 */
	public function get(string $uuid): Newcomer;

	/**
	 * Get all newcomers.
	 *
	 * @return Newcomer[]
	 */
	public function getAll(): array;

	/**
	 * Add a newcomer to the catalog.
	 */
	public function add(Newcomer $newcomer): Debut;

	/**
	 * Remove a newcomer from the catalog.
	 */
	public function remove(Newcomer $newcomer): Debut;

	/**
	 * Load newcomers data.
	 */
	public function load(): Debut;

	/**
	 * Save newcomers data.
	 */
	public function save(): Debut;

	/**
	 * Clear all newcomers.
	 */
	public function clear(): Debut;
}
