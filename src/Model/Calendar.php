<?php
declare (strict_types = 1);
namespace Lemuria\Model;

use JetBrains\PhpStorm\Pure;

use Lemuria\Serializable;

/**
 * A Calendar knows current time in the game.
 */
interface Calendar extends Serializable
{
	/**
	 * Get the month.
	 */
	#[Pure] public function Month(): int;

	/**
	 * Get the game round.
	 */
	#[Pure] public function Round(): int;

	/**
	 * Get the season.
	 */
	#[Pure] public function Season(): int;

	/**
	 * Get the week of the month.
	 */
	#[Pure] public function Week(): int;

	/**
	 * Get the year.
	 */
	#[Pure] public function Year(): int;

	/**
	 * Load game data.
	 */
	public function load(): Calendar;

	/**
	 * Save game data.
	 */
	public function save(): Calendar;

	/**
	 * Advance the Calendar to next round and return the new round.
	 */
	public function nextRound(): int;
}
