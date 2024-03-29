<?php
declare(strict_types = 1);

namespace Lemuria\Statistics\Metrics;

use Lemuria\Identifiable;
use Lemuria\Statistics\Data;
use Lemuria\Statistics\Metrics;

class BaseMetrics implements Metrics
{
	public function __construct(protected string $subject = '', protected ?Identifiable $entity = null) {
	}

	public function Entity(): ?Identifiable {
		return $this->entity;
	}

	public function Subject(): string {
		return $this->subject;
	}

	public function setEntity(Identifiable $entity): static {
		$this->entity = $entity;
		return $this;
	}

	public function setSubject(string $subject): static {
		$this->subject = $subject;
		return $this;
	}

	public function Data(): ?Data {
		return null;
	}
}
