<?php
declare(strict_types = 1);
namespace Lemuria\Tests\Mock\Engine;

use Lemuria\Engine\Debut;
use Lemuria\Engine\Newcomer;
use Lemuria\Exception\UnknownUuidException;

class DebutMock implements Debut
{
	public function count(): int {
		return 0;
	}

	/**
	 * @throws UnknownUuidException
	 */
	public function get(string $uuid): Newcomer {
		throw new UnknownUuidException($uuid);
	}

	public function getAll(): array {
		return [];
	}

	public function add(Newcomer $newcomer): static {
		return $this;
	}

	public function remove(Newcomer $newcomer): static {
		return $this;
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
