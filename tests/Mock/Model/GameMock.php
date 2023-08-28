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

	public function getRealms(): array {
		return [];
	}

	public function getStatistics(): array {
		return [];
	}

	public function getScripts(): array {
		return [];
	}

	public function getStrings(): array {
		return [];
	}

	public function setCalendar(array $calendar): static {
		return $this;
	}

	public function setConstructions(array $constructions): static {
		return $this;
	}

	public function setMessages(array $messages): static {
		return $this;
	}

	public function setParties(array $parties): static {
		return $this;
	}

	public function setOrders(array $orders): static {
		return $this;
	}

	public function setRegions(array $regions): static {
		return $this;
	}

	public function setUnits(array $units): static {
		return $this;
	}

	public function setVessels(array $vessels): static {
		return $this;
	}

	public function setWorld(array $world): static {
		return $this;
	}

	public function setEffects(array $effects): static {
		return $this;
	}

	public function setNewcomers(array $newcomers): static {
		return $this;
	}

	public function setContinents(array $continents): static {
		return $this;
	}

	public function setHostilities(array $hostilities): static {
		return $this;
	}

	public function setUnica(array $unica): static {
		return $this;
	}

	public function setTrades(array $trades): static {
		return $this;
	}

	public function setRealms(array $realms): static {
		return $this;
	}

	public function setStatistics(array $statistics): static {
		return $this;
	}

	public function setScripts(array $scripts): static {
		return $this;
	}

	public function migrate(): static {
		return $this;
	}
}
