<?php
declare (strict_types = 1);
namespace Lemuria\Model;

use Lemuria\Model\Calendar\Season;
use Lemuria\Serializable;

/**
 * A Calendar knows current time in the game.
 */
interface Calendar extends Serializable
{
	/**
	 * Get the month.
	 */
	public function Month(): int;

	/**
	 * Get the game round.
	 */
	public function Round(): int;

	/**
	 * Get the season.
	 */
	public function Season(): Season;

	/**
	 * Get the week of the month.
	 */
	public function Week(): int;

	/**
	 * Get the year.
	 */
	public function Year(): int;

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

	/**
	 * Get the required version for the game model containing this calendar.
	 */
	public function getCompatibility(): string;
}
