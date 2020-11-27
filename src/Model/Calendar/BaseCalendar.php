<?php
declare (strict_types = 1);
namespace Lemuria\Model\Calendar;

use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

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
 * year has SEASONS*MONTH*WEEKS weeks or rounds.
 */
class BaseCalendar implements Calendar
{
	use SerializableTrait;

	protected int $weeks = 3;

	protected int $months = 2;

	protected int $seasons = 4;

	private int $round = 0;

	/**
	 * Get a plain data array of the model's data.
	 */
	#[ArrayShape(['round' => 'int'])]
	#[Pure]
	public function serialize(): array {
		return ['round' => $this->round];
	}

	/**
	 * Restore the model's data from serialized data.
	 */
	public function unserialize(array $data): Serializable {
		$this->validateSerializedData($data);
		$this->round = $data['round'];
		return $this;
	}

	/**
	 * Get the month.
	 */
	#[Pure] public function Month(): int {
		return $this->round % ($this->seasons * $this->months) + 1;
	}

	/**
	 * Get the game round.
	 */
	#[Pure] public function Round(): int {
		return $this->round + 1;
	}

	/**
	 * Get the season.
	 */
	#[Pure] public function Season(): int {
		return (int)($this->round / ($this->months * $this->weeks)) % $this->seasons + 1;
	}

	/**
	 * Get the week of the month.
	 */
	#[Pure] public function Week(): int {
		return $this->round % $this->weeks + 1;
	}

	/**
	 * Get the year.
	 */
	#[Pure] public function Year(): int {
		return (int)floor($this->round / ($this->seasons * $this->months * $this->weeks)) + 1;
	}

	/**
	 * Load game data.
	 */
	public function load(): Calendar {
		$this->unserialize(Lemuria::Game()->getCalendar());
		return $this;
	}

	/**
	 * Save game data.
	 */
	public function save(): Calendar {
		Lemuria::Game()->setCalendar($this->serialize());
		return $this;
	}

	/**
	 * Advance the Calendar to next round and return the new round.
	 */
	public function nextRound(): int {
		$this->round++;
		return $this->Round();
	}

	/**
	 * Check that a serialized data array is valid.
	 *
	 * @param array (string=>mixed) &$data
	 * @throws UnserializeEntityException
	 */
	protected function validateSerializedData(&$data): void {
		$this->validate($data, 'round', 'int');
	}
}
