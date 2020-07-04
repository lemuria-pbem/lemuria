<?php
declare (strict_types = 1);
namespace Lemuria;

/**
 * A Game instance handles persistence of game data.
 */
interface Game
{
	/**
	 * Get the calendar data.
	 *
	 * @return array
	 */
	public function getCalendar(): array;

	/**
	 * Get the constructions data.
	 *
	 * @return array
	 */
	public function getConstructions(): array;

	/**
	 * Get the parties data.
	 *
	 * @return array
	 */
	public function getParties(): array;

	/**
	 * Get the regions data.
	 *
	 * @return array
	 */
	public function getRegions(): array;

	/**
	 * Get the units data.
	 *
	 * @return array
	 */
	public function getUnits(): array;

	/**
	 * Get the vessels data.
	 *
	 * @return array
	 */
	public function getVessels(): array;

	/**
	 * Get the world data.
	 *
	 * @return array
	 */
	public function getWorld(): array;

	/**
	 * Get string data.
	 *
	 * @return array
	 */
	public function getStrings(): array;

	/**
	 * Set the calendar data.
	 *
	 * @param array $calendar
	 * @return Game
	 */
	public function setCalendar(array $calendar): Game;

	/**
	 * Set the constructions data.
	 *
	 * @param array $constructions
	 * @return Game
	 */
	public function setConstructions(array $constructions): Game;

	/**
	 * Set the parties data.
	 *
	 * @param array $parties
	 * @return Game
	 */
	public function setParties(array $parties): Game;

	/**
	 * Set the regions data.
	 *
	 * @param array $regions
	 * @return Game
	 */
	public function setRegions(array $regions): Game;

	/**
	 * Set the units data.
	 *
	 * @param array $units
	 * @return Game
	 */
	public function setUnits(array $units): Game;

	/**
	 * Set the vessels data.
	 *
	 * @param array $vessels
	 * @return Game
	 */
	public function setVessels(array $vessels): Game;

	/**
	 * Set the world data.
	 *
	 * @param array $world
	 * @return Game
	 */
	public function setWorld(array $world): Game;
}
