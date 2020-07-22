<?php
declare (strict_types = 1);
namespace Lemuria\Engine;

/**
 * Main engine.
 */
interface Turn
{
	/**
	 * Add commands.
	 *
	 * @param Move $move
	 * @return Turn
	 */
	public function add(Move $move): Turn;

	/**
	 * Evaluate the whole turn.
	 *
	 * @return Turn
	 */
	public function evaluate(): Turn;
}
