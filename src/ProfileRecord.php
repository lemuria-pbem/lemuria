<?php
declare(strict_types = 1);
namespace Lemuria;

class ProfileRecord implements \Stringable
{
	protected float $previous = NAN;

	private readonly int $memory;

	private readonly int $realMemory;

	private readonly int $peakMemory;

	private readonly int $realPeakMemory;

	public function __construct(private readonly float $timestamp) {
		$this->memory         = memory_get_usage();
		$this->realMemory     = memory_get_usage(true);
		$this->peakMemory     = memory_get_peak_usage();
		$this->realPeakMemory = memory_get_peak_usage(true);
		memory_reset_peak_usage();
	}

	public function Memory(): int {
		return $this->memory;
	}

	public function PeakMemory(): int {
		return $this->peakMemory;
	}

	public function RealMemory(): int {
		return $this->realMemory;
	}

	public function RealPeakMemory(): int {
		return $this->realPeakMemory;
	}

	public function Timestamp(): float {
		return $this->timestamp;
	}

	public function setPrevious(float $timestamp): static {
		$this->previous = $timestamp;
		return $this;
	}

	public function __toString(): string {
		$timestamp  = date('Y-m-d H:i:s', (int)$this->timestamp);
		$us         = (int)round(($this->timestamp - (int)$this->timestamp) * 1000000);
		$duration   = $this->createDuration();
		$memory     = memory($this->memory) . ' (' . memory($this->peakMemory) . ' peak)';
		$realMemory = memory($this->realMemory) . ' (' . memory($this->realPeakMemory) . ' peak)';
		return $timestamp . '.' . $us . $duration . ': ' . $memory . ', ' . $realMemory . ' real';
	}

	protected function createDuration(): string {
		if (is_nan($this->previous)) {
			return '';
		}
		$duration = $this->timestamp - $this->previous;
		if ($duration < 1.0)  {
			$duration *= 1000.0;
			if ($duration < 1.0) {
				$duration *= 1000.0;
				return ' (' . round($duration) . 'µs)';
			}
			return ' (' . round($duration) . 'ms)';
		} elseif ($duration >= 60.0) {
			$minutes = floor($duration / 60.0);
			$seconds = round($duration - $minutes * 60.0);
			$seconds = $seconds < 10.0 ? '0' . $seconds : (string)$seconds;
			return ' (' . $minutes . ':' . $seconds . 'min)';
		}
		return ' (' . round($duration, 1) . 's)';
	}
}
