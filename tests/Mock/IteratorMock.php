<?php
declare(strict_types = 1);
namespace Lemuria\Tests\Mock;

use Lemuria\IteratorTrait;

class IteratorMock implements \Countable, \Iterator
{
	use IteratorTrait;

	protected array $items = [];

	public function current() {
		return $this->items[$this->index];
	}

	public function add(string $item): void {
		$this->items[] = $item;
		$this->count++;
	}
}
