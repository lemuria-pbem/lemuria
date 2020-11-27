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
	 */
	public function add(Move $move): Turn;

	/**
	 * Evaluate the whole turn.
	 */
	public function evaluate(): Turn;
}
