<?php
declare (strict_types = 1);
namespace Lemuria\Engine;

use Lemuria\EntitySet;
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
	public function add(Move $move): EntitySet;

	/**
	 * Bring a new party into the game.
	 */
	public function initiate(Newcomer $newcomer): Turn;

	/**
	 * Add default commands of given entity.
	 */
	public function substitute(Identifiable $entity): Turn;

	/**
	 * Evaluate the whole turn.
	 */
	public function evaluate(): Turn;

	/**
	 * Get the version of the engine package of this engine.
	 */
	public function getVersion(): VersionTag;
}
