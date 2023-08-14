<?php
declare (strict_types = 1);
namespace Lemuria\Engine;

use Lemuria\Identifiable;
use Lemuria\Version\VersionTag;

/**
 * Main engine.
 */
interface Turn
{
	/**
	 * Add commands.
	 */
	public function add(Move $move): static;

	/**
	 * Bring a new party into the game.
	 */
	public function initiate(Newcomer $newcomer): static;

	/**
	 * Add default commands of given entity.
	 */
	public function substitute(Identifiable $entity): static;

	/**
	 * Evaluate the whole turn.
	 */
	public function evaluate(): static;

	/**
	 * Get the version of the engine package of this engine.
	 */
	public function getVersion(): VersionTag;
}
