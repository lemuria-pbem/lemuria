<?php
declare(strict_types = 1);
namespace Lemuria\Tests\Mock;

use Lemuria\Assignable;
use Lemuria\Registry;

class RegistryMock implements Registry
{
	public function count(): int {
		return 0;
	}

	public function find(string $uuid): ?Assignable {
		return null;
	}
}
