<?php
declare(strict_types = 1);
namespace Lemuria\Engine;

use Lemuria\Exception\UnknownUuidException;

/**
 * A catalog for newcomers.
 */
interface Debut extends \Countable
{
	/**
	 * Get a Newcomer.
	 *
	 * @throws UnknownUuidException
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
	 *
	 * If a newcomer with the same UUID already exists, it is overwritten.
	 */
	public function add(Newcomer $newcomer): Debut;

	/**
	 * Remove a newcomer from the catalog.
	 *
	 * If there is no such newcomer registered nothing happens.
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
