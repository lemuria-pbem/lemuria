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

	private readonly float $hourZero;

	private float $previous = NAN;

	private int $index = 0;

	/**
	 * @var array<string, ProfileRecord>
	 */
	protected array $records = [];

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

	public function record(string $identifier): ProfileRecord {
		return $this->createRecord($identifier, microtime(true));
	}

	public function recordAndLog(string $identifier): void {
		$record = $this->record($identifier);
		Lemuria::Log()->debug('Profiler [' . $identifier . ']: ' . $record);
	}

	public function recordTotal(string $identifier = self::RECORD_TOTAL): void {
		$record = $this->record($identifier);
		Lemuria::Log()->debug('Profiler [' . $identifier . ']: ' . $record->setPrevious($this->hourZero));
	}

	public function logRecord(string|array $identifier): void {
		if (is_string($identifier)) {
			Lemuria::Log()->debug('Profiler [' . $identifier . ']: ' . $this->getRecord($identifier));
		} else {
			foreach ($identifier as $current) {
				Lemuria::Log()->debug('Profiler [' . $current . ']: ' . $this->getRecord($current));
			}
		}
	}

	public function logTotalPeak(): void {
		$peak = $this->getPeakMemory();
		$realPeak = $this->getRealPeakMemory();
		Lemuria::Log()->debug('Profiler: Peak memory ' . memory($peak) . ' (' . memory($realPeak) . ' real).');
	}

	protected function createRecord(string $identifier, float $timestamp): ProfileRecord {
		if (isset($this->records[$identifier])) {
			throw new LemuriaException('There is already a record with this identifier.');
		}
		$record                     = new ProfileRecord($timestamp);
		$this->records[$identifier] = $record->setPrevious($this->previous);
		$this->previous             = $timestamp;
		return $record;
	}
}
