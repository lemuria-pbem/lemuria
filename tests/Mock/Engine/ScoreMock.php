<?php
declare(strict_types = 1);
namespace Lemuria\Tests\Mock\Engine;

use Lemuria\Engine\Score;
use Lemuria\Identifiable;

class ScoreMock implements Score
{
	public function current(): null {
		return null;
	}

	public function next(): void {
	}

	public function key(): null {
		return null;
	}

	public function valid(): bool {
		return false;
	}

	public function rewind(): void {
	}

	public function find(Identifiable $effect): ?Identifiable {
		return null;
	}

	public function findAll(Identifiable $entity): array {
		return [];
	}

	public function add(Identifiable $effect): Score {
		return $this;
	}

	public function remove(Identifiable $effect): Score {
		return $this;
	}

	public function load(): Score {
		return $this;
	}

	public function save(): Score {
		return $this;
	}
}
