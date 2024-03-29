<?php
declare(strict_types = 1);
namespace Lemuria\Tests\Mock\Engine;

use Lemuria\Engine\Exception\NotRegisteredException;
use Lemuria\Engine\Message;
use Lemuria\Engine\Report;
use Lemuria\Id;
use Lemuria\Identifiable;

class ReportMock implements Report
{
	public function get(Id $id): Message {
		throw new NotRegisteredException($id);
	}

	public function getAll(Identifiable $entity): array {
		return [];
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

	public function register(Message $message): static {
		return $this;
	}

	public function nextId(): Id {
		return new Id(1);
	}
}
