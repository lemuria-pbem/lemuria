<?php
declare(strict_types = 1);
namespace Lemuria\Tests\Mock\Model;

use Lemuria\Model\Game;

class GameMock implements Game
{
	public function getCalendar(): array {
		return [];
	}

	public function getConstructions(): array {
		return [];
	}

	public function getMessages(): array {
		return [];
	}

	public function getParties(): array {
		return [];
	}

	public function getOrders(): array {
		return [];
	}

	public function getRegions(): array {
		return [];
	}

	public function getUnits(): array {
		return [];
	}

	public function getVessels(): array {
		return [];
	}

	public function getWorld(): array {
		return [];
	}

	public function getEffects(): array {
		return [];
	}

	public function getNewcomers(): array {
		return [];
	}

	public function getContinents(): array {
		return [];
	}

	public function getHostilities(): array {
		return [];
	}

	public function getUnica(): array {
		return [];
	}

	public function getTrades(): array {
		return [];
	}

	public function getStatistics(): array {
		return [];
	}

	public function getStrings(): array {
		return [];
	}

	public function setCalendar(array $calendar): Game {
		return $this;
	}

	public function setConstructions(array $constructions): Game {
		return $this;
	}

	public function setMessages(array $messages): Game {
		return $this;
	}

	public function setParties(array $parties): Game {
		return $this;
	}

	public function setOrders(array $orders): Game {
		return $this;
	}

	public function setRegions(array $regions): Game {
		return $this;
	}

	public function setUnits(array $units): Game {
		return $this;
	}

	public function setVessels(array $vessels): Game {
		return $this;
	}

	public function setWorld(array $world): Game {
		return $this;
	}

	public function setEffects(array $effects): Game {
		return $this;
	}

	public function setNewcomers(array $newcomers): Game {
		return $this;
	}

	public function setContinents(array $continents): Game {
		return $this;
	}

	public function setHostilities(array $hostilities): Game {
		return $this;
	}

	public function setUnica(array $unica): Game {
		return $this;
	}

	public function setTrades(array $trades): Game {
		return $this;
	}

	public function setStatistics(array $statistics): Game {
		return $this;
	}

	public function migrate(): Game {
		return $this;
	}
}
