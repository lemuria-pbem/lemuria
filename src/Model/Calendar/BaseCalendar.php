<?php
declare (strict_types = 1);
namespace Lemuria\Model\Calendar;

use Lemuria\Exception\UnserializeEntityException;
use Lemuria\Lemuria;
use Lemuria\Model\Calendar;
use Lemuria\SerializableTrait;
use Lemuria\Validate;

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

	private const string ROUND = 'round';

	private const string VERSION = 'version';

	protected int $weeks = 3;

	protected int $months = 2;

	protected int $seasons = 4;

	private int $round = 0;

	private int $r = 0;

	private string $version = '';

	/**
	 * Get a plain data array of the model's data.
	 *
	 * @return array<string, int>
	 */
	public function serialize(): array {
		return [self::ROUND => $this->round, self::VERSION => $this->version];
	}

	/**
	 * Restore the model's data from serialized data.
	 *
	 * @param array<string, int> $data
	 */
	public function unserialize(array $data): static {
		$this->validateSerializedData($data);
		$this->setRound($data[self::ROUND]);
		$this->version = $data[self::VERSION];
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
	public function Season(): Season {
		$season = (int)($this->r / ($this->months * $this->weeks)) % $this->seasons + 1;
		return Season::from($season);
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

	public function load(): static {
		$this->unserialize(Lemuria::Game()->getCalendar());
		return $this;
	}

	public function save(): static {
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
	 * Get the required version for the game model containing this calendar.
	 */
	public function getCompatibility(): string {
		return $this->version;
	}

	/**
	 * @param array<string, int> $data
	 * @throws UnserializeEntityException
	 */
	protected function validateSerializedData($data): void {
		$this->validate($data, self::ROUND, Validate::Int);
		$this->validate($data, self::VERSION, Validate::String);
	}

	protected function setRound(int $round): void
	{
		$this->round = $round;
		$this->r     = $round - 1;
	}
}
