<?php
declare(strict_types = 1);
namespace Lemuria;

trait CountableTrait
{
	private int $count = 0;

	public function count(): int {
		return $this->count;
	}

	public function isEmpty(): bool {
		return $this->count <= 0;
	}
}
