<?php
declare (strict_types = 1);
namespace Lemuria\Engine;

use Lemuria\Identifiable;
use Lemuria\Model\Newcomer;

/**
 * Main engine.
 */
interface Turn
{
	/**
	 * Add commands.
	 */
	public function add(Move $move): Turn;

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
}
