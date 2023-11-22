<?php
declare(strict_types = 1);
namespace Lemuria\Tests\Mock\Scenario;

use Lemuria\Scenario\Scripts;

class ScriptsMock implements Scripts
{
	public function load(): static {
		return $this;
	}

	public function save(): static {
		return $this;
	}

	public function play(): static {
		return $this;
	}
}
