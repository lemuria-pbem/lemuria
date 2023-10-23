<?php
declare(strict_types = 1);
namespace Lemuria\Storage\Ini;

use Lemuria\IteratorTrait;

class Value implements \Countable, \Iterator, \Stringable
{
	use IteratorTrait;

	protected array $value;

	public function __construct(string $value) {
		$this->value = [$value];
		$this->count++;
	}

	public function __toString(): string {
		return $this->value[$this->count - 1];
	}

	public function current(): mixed {
		return $this->value[$this->index];
	}

	public function add(string $value): static {
		$this->value[] = $value;
		$this->count++;
		return $this;
	}
}
