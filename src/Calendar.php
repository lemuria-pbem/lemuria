<?php
declare (strict_types = 1);
namespace Lemuria;

/**
 * A Calendar knows current time in the game.
 */
interface Calendar extends Serializable
{
	/**
	 * Get the month.
	 *
	 * @return int
	 */
	public function Month(): int;

	/**
	 * Get the game round.
	 *
	 * @return int
	 */
	public function Round(): int;

	/**
	 * Get the season.
	 *
	 * @return int
	 */
	public function Season(): int;

	/**
	 * Get the week of the month.
	 *
	 * @return int
	 */
	public function Week(): int;

	/**
	 * Get the year.
	 *
	 * @return int
	 */
	public function Year(): int;

	/**
	 * Load game data.
	 *
	 * @return Calendar
	 */
	public function load(): Calendar;

	/**
	 * Save game data.
	 *
	 * @return Calendar
	 */
	public function save(): Calendar;

	/**
	 * Advance the Calendar to next round and return the new round.
	 *
	 * @return int
	 */
	public function nextRound(): int;
}
