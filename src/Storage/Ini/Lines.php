<?php
declare(strict_types = 1);
namespace Lemuria\Storage\Ini;

use Lemuria\IteratorTrait;

class Lines implements \Countable, \Iterator
{
	use IteratorTrait;

	/**
	 * @var array<string>
	 */
	private array $lines = [];

	public function current(): string {
		return $this->lines[$this->key()];
	}

	public function add(string $line): static {
		$this->lines[] = trim($line);
		$this->count++;
		return $this;
	}

	public function clear(): static {
		$this->lines = [];
		$this->count = 0;
		return $this;
	}
}
