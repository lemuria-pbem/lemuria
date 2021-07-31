<?php
declare (strict_types = 1);
namespace Lemuria\Model;

/**
 * A Game instance handles persistence of game data.
 */
interface Game
{
	/**
	 * Get the calendar data.
	 */
	public function getCalendar(): array;

	/**
	 * Get the constructions' data.
	 */
	public function getConstructions(): array;

	/**
	 * Get the report messages.
	 */
	public function getMessages(): array;

	/**
	 * Get the parties' data.
	 */
	public function getParties(): array;

	/**
	 * Get the orders' data.
	 */
	public function getOrders(): array;

	/**
	 * Get the regions' data.
	 */
	public function getRegions(): array;

	/**
	 * Get the units' data.
	 */
	public function getUnits(): array;

	/**
	 * Get the vessels' data.
	 */
	public function getVessels(): array;

	/**
	 * Get the world data.
	 */
	public function getWorld(): array;

	/**
	 * Get the effects' data.
	 */
	public function getEffects(): array;

	/**
	 * Get the newcomers' data.
	 */
	public function getNewcomers(): array;

	/**
	 * Get the continent data.
	 */
	public function getContinents(): array;

	/**
	 * Get string data.
	 */
	public function getStrings(): array;

	/**
	 * Set the calendar data.
	 */
	public function setCalendar(array $calendar): Game;

	/**
	 * Set the constructions' data.
	 */
	public function setConstructions(array $constructions): Game;

	/**
	 * Set the report messages.
	 */
	public function setMessages(array $messages): Game;

	/**
	 * Set the parties' data.
	 */
	public function setParties(array $parties): Game;

	/**
	 * Set the orders' data.
	 */
	public function setOrders(array $orders): Game;

	/**
	 * Set the regions' data.
	 */
	public function setRegions(array $regions): Game;

	/**
	 * Set the units' data.
	 */
	public function setUnits(array $units): Game;

	/**
	 * Set the vessels' data.
	 */
	public function setVessels(array $vessels): Game;

	/**
	 * Set the world data.
	 */
	public function setWorld(array $world): Game;

	/**
	 * Set the effects' data.
	 */
	public function setEffects(array $effects): Game;

	/**
	 * Set the newcomers' data.
	 */
	public function setNewcomers(array $newcomers): Game;

	/**
	 * Set the continent data.
	 */
	public function setContinents(array $continents): Game;
}
