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
	 * Get the hostilities' data.
	 */
	public function getHostilities(): array;

	/**
	 * Get the unica data.
	 */
	public function getUnica(): array;

	/**
	 * Get the trades' data.
	 */
	public function getTrades(): array;

	/**
	 * Get the realms' data.
	 */
	public function getRealms(): array;

	/**
	 * Get statistical data.
	 */
	public function getStatistics(): array;

	/**
	 * Get string data.
	 */
	public function getStrings(): array;

	/**
	 * Set the calendar data.
	 */
	public function setCalendar(array $calendar): static;

	/**
	 * Set the constructions' data.
	 */
	public function setConstructions(array $constructions): static;

	/**
	 * Set the report messages.
	 */
	public function setMessages(array $messages): static;

	/**
	 * Set the parties' data.
	 */
	public function setParties(array $parties): static;

	/**
	 * Set the orders' data.
	 */
	public function setOrders(array $orders): static;

	/**
	 * Set the regions' data.
	 */
	public function setRegions(array $regions): static;

	/**
	 * Set the units' data.
	 */
	public function setUnits(array $units): static;

	/**
	 * Set the vessels' data.
	 */
	public function setVessels(array $vessels): static;

	/**
	 * Set the world data.
	 */
	public function setWorld(array $world): static;

	/**
	 * Set the effects' data.
	 */
	public function setEffects(array $effects): static;

	/**
	 * Set the newcomers' data.
	 */
	public function setNewcomers(array $newcomers): static;

	/**
	 * Set the continent data.
	 */
	public function setContinents(array $continents): static;

	/**
	 * Set the hostilities' data.
	 */
	public function setHostilities(array $hostilities): static;

	/**
	 * Set the unica data.
	 */
	public function setUnica(array $unica): static;

	/**
	 * Set the trades' data.
	 */
	public function setTrades(array $trades): static;

	/**
	 * Set the realms' data.
	 */
	public function setRealms(array $realms): static;

	/**
	 * Set statistical data.
	 */
	public function setStatistics(array $statistics): static;

	/**
	 * Migrate entities if needed.
	 */
	public function migrate(): static;
}
