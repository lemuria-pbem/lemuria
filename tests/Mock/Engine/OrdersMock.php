<?php
declare(strict_types = 1);
namespace Lemuria\Tests\Mock\Engine;

use Lemuria\Engine\Instructions;
use Lemuria\Engine\Orders;
use Lemuria\Id;
use Lemuria\StringList;

class OrdersMock implements Orders
{
	public function getCurrent(Id $id): Instructions {
		return new StringList();
	}

	public function getDefault(Id $id): Instructions {
		return new StringList();
	}

	public function load(): static {
		return $this;
	}

	public function save(): static {
		return $this;
	}

	public function clear(): static {
		return $this;
	}
}
