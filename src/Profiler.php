<?php
declare(strict_types = 1);
namespace Lemuria;

use Lemuria\Exception\LemuriaException;
use Lemuria\Exception\NotImplementedException;
use Lemuria\Model\Builder;

class Profiler implements \ArrayAccess, \Countable, \Iterator
{
	/**
	 * The name of an environment variable that contains the zero-hour timestamp.
	 */
	public final const string LEMURIA_ZERO_HOUR = 'LEMURIA_ZERO_HOUR';

	public final const string RECORD_ZERO = __CLASS__ . '::RECORD_ZERO';

	public final const string RECORD_BUILDER = Builder::class . '::REGISTRATION_DONE';

	public final const string RECORD_TOTAL = __CLASS__ . '::RECORD_TOTAL';

	private bool $isEnabled = true;

	private readonly float $hourZero;

	private float $previous = NAN;

	private float $sumBase;

	private int $index = 0;

	/**
	 * @var array<string, ProfileRecord>
	 */
	protected array $records = [];

	private bool $withSum = false;

	public function __construct() {
		$now      = microtime(true);
		$hourZero = getenv(self::LEMURIA_ZERO_HOUR);
		if (is_string($hourZero)) {
			$hourZero = floatval($hourZero);
			if ($hourZero > 0.0) {
				$now = round($hourZero, 6);
			}
		}
		$this->hourZero = $now;
		$this->createRecord(self::RECORD_ZERO, $this->hourZero);
		$this->resetSum();
	}

	/**
	 * @var string $offset
	 */
	public function offsetExists(mixed $offset): bool {
		return is_string($offset) && isset($this->records[$offset]);
	}

	/**
	 * @var string $offset
	 */
	public function offsetGet(mixed $offset): ProfileRecord {
		if (is_string($offset)) {
			return $this->getRecord($offset);
		}
		throw new LemuriaException();
	}

	public function offsetSet(mixed $offset, mixed $value): void {
		throw new NotImplementedException();
	}

	public function offsetUnset(mixed $offset): void {
		throw new NotImplementedException();
	}

	public function count(): int {
		return count($this->records);
	}

	public function current(): ProfileRecord {
		return $this->records[$this->key()];
	}

	public function key(): string {
		$keys = array_keys($this->records);
		if ($this->valid()) {
			return $keys[$this->index];
		}
		throw new LemuriaException();
	}

	public function next(): void {
		$this->index++;
	}

	public function rewind(): void {
		$this->index = 0;
	}

	public function valid(): bool {
		return $this->index < $this->count();
	}

	public function isEnabled(): bool {
		return $this->isEnabled;
	}

	public function setEnabled(bool $enabled): static {
		$this->isEnabled = $enabled;
		return $this;
	}

	public function getRecord(string $identifier): ProfileRecord {
		if (!isset($this->records[$identifier])) {
			throw new LemuriaException('There is no record with this identifier.');
		}
		return $this->records[$identifier];
	}

	public function getPeakMemory(): int {
		$peak = 0;
		foreach ($this->records as $record) {
			$peak = max($peak, $record->PeakMemory());
		}
		return $peak;
	}

	public function getRealPeakMemory(): int {
		$peak = 0;
		foreach ($this->records as $record) {
			$peak = max($peak, $record->RealPeakMemory());
		}
		return $peak;
	}

	public function sum(): static {
		$this->withSum = true;
		return $this;
	}

	public function record(string $identifier): static {
		$this->createRecord($identifier, microtime(true));
		return $this;
	}

	public function recordAndLog(string $identifier): static {
		$record = $this->createRecord($identifier, microtime(true));
		Lemuria::Log()->debug('Profiler [' . $identifier . ']: ' . $record);
		return $this;
	}

	public function recordTotal(string $identifier = self::RECORD_TOTAL): static {
		$record = $this->createRecord($identifier, microtime(true));
		Lemuria::Log()->debug('Profiler [' . $identifier . ']: ' . $record->setPrevious($this->hourZero));
		return $this;
	}

	public function logRecord(string|array $identifier): static {
		if (is_string($identifier)) {
			Lemuria::Log()->debug('Profiler [' . $identifier . ']: ' . $this->getRecord($identifier));
		} else {
			foreach ($identifier as $current) {
				Lemuria::Log()->debug('Profiler [' . $current . ']: ' . $this->getRecord($current));
			}
		}
		return $this;
	}

	public function logTotalPeak(): static {
		$peak = $this->getPeakMemory();
		$realPeak = $this->getRealPeakMemory();
		Lemuria::Log()->debug('Profiler: Peak memory ' . memory($peak) . ' (' . memory($realPeak) . ' real).');
		return $this;
	}

	public function resetSum(): static {
		$this->sumBase = $this->previous;
		return $this;
	}

	protected function createRecord(string $identifier, float $timestamp): ProfileRecord {
		if (isset($this->records[$identifier])) {
			throw new LemuriaException('There is already a record with this identifier.');
		}
		$record                     = new ProfileRecord($timestamp);
		$this->records[$identifier] = $record->setPrevious($this->previous);
		$this->previous             = $timestamp;
		if ($this->withSum) {
			$record->setSumBase($this->sumBase);
			$this->withSum = false;
		}
		return $record;
	}
}
