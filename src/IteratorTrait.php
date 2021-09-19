<?php
declare(strict_types = 1);
namespace Lemuria;

trait IteratorTrait
{
	private array $iteratorArray;

	private int $index = 0;

	private int $count = 0;

	public function current(): mixed {
		return $this->iteratorArray[$this->index];
	}

	public function key(): int {
		return $this->index;
	}

	public function next(): void {
		$this->index++;
	}

	public function rewind(): void {
		$this->index = 0;
	}

	public function valid(): bool {
		return $this->index < $this->count;
	}

	protected function initIterator(array $array): void {
		$this->iteratorArray = $array;
	}
}
