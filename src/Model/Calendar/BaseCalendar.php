<?php
declare (strict_types = 1);
namespace Lemuria\Model\Calendar;

use Lemuria\Exception\UnserializeEntityException;
use Lemuria\Lemuria;
use Lemuria\Model\Calendar;
use Lemuria\Serializable;
use Lemuria\SerializableTrait;

/**
 * Implementation of a base calendar with round counter.
 *
 * The counter starts with zero which means that the first round is yet to come, which is round one. A round is a week.
 * A year consists of the seasons with a defined number of months each. A month has a defined number of weeks, so every
 * year has SEASONS*MONTHS*WEEKS rounds (each as long as a week).
 */
class BaseCalendar implements Calendar
{
	use SerializableTrait;

	protected int $weeks = 3;

	protected int $months = 2;

	protected int $seasons = 4;

	private int $round = 0;

	private int $r = 0;

	/**
	 * Get a plain data array of the model's data.
	 *
	 * @return array<string, int>
	 */
	public function serialize(): array {
		return ['round' => $this->round];
	}

	/**
	 * Restore the model's data from serialized data.
	 *
	 * @param array<string, int> $data
	 */
	public function unserialize(array $data): Serializable {
		$this->validateSerializedData($data);
		$this->setRound($data['round']);
		return $this;
	}

	/**
	 * Get the month.
	 */
	public function Month(): int {
		return (int)($this->r / $this->weeks) % ($this->months * $this->seasons) + 1;
	}

	/**
	 * Get the game round.
	 */
	public function Round(): int {
		return $this->round;
	}

	/**
	 * Get the season.
	 */
	public function Season(): int {
		return (int)($this->r / ($this->months * $this->weeks)) % $this->seasons + 1;
	}

	/**
	 * Get the week of the month.
	 */
	public function Week(): int {
		return $this->r % $this->weeks + 1;
	}

	/**
	 * Get the year.
	 */
	public function Year(): int {
		return (int)floor($this->r / ($this->seasons * $this->months * $this->weeks)) + 1;
	}

	public function load(): Calendar {
		$this->unserialize(Lemuria::Game()->getCalendar());
		return $this;
	}

	public function save(): Calendar {
		Lemuria::Game()->setCalendar($this->serialize());
		return $this;
	}

	/**
	 * Advance the Calendar to next round and return the new round.
	 */
	public function nextRound(): int {
		$this->setRound($this->round + 1);
		return $this->Round();
	}

	/**
	 * @param array<string, int> $data
	 * @throws UnserializeEntityException
	 */
	protected function validateSerializedData(&$data): void {
		$this->validate($data, 'round', 'int');
	}

	protected function setRound(int $round): void
	{
		$this->round = $round;
		$this->r     = $round - 1;
	}
}
