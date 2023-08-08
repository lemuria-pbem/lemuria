<?php
declare(strict_types = 1);
namespace Lemuria;

readonly class ProfileRecord implements \Stringable
{
	private int $memory;

	private int $realMemory;

	private int $peakMemory;

	private int $realPeakMemory;

	public function __construct(private float $timestamp) {
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

	public function __toString(): string {
		$timestamp  = date('Y-m-d H:i:s', (int)$this->timestamp);
		$us         = (int)round(($this->timestamp - (int)$this->timestamp) * 1000000);
		$memory     = memory($this->memory) . ' (' . memory($this->peakMemory) . ' peak)';
		$realMemory = memory($this->realMemory) . ' (' . memory($this->realPeakMemory) . ' peak)';
		return $timestamp . '.' . $us . ': ' . $memory . ', ' . $realMemory . ' real';
	}
}
