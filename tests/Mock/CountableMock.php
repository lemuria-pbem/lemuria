<?php
declare(strict_types = 1);
namespace Lemuria\Tests\Mock;

use Lemuria\CountableTrait;

class CountableMock implements \Countable
{
	use CountableTrait;

	public function increase(): void {
		$this->count++;
	}
}
