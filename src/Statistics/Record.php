<?php
declare(strict_types = 1);
namespace Lemuria\Statistics;

use Lemuria\Identifiable;

/**
 * Represents statistical data records.
 */
class Record
{
	private ?Data $data = null;

	public static function from(Metrics $metrics): static {
		return new self($metrics->Subject(), $metrics->Entity());
	}

	public function __construct(private readonly string $subject, private readonly ?Identifiable $entity = null) {
	}

	public function Subject(): string {
		return $this->subject;
	}

	public function Entity(): ?Identifiable {
		return $this->entity;
	}

	public function Data(): ?Data {
		return $this->data;
	}

	public function Key(): string {
		if ($this->entity) {
			return $this->entity->Catalog()->value . '.' . $this->entity->Id()->Id() . '.' . $this->subject;
		}
		return $this->subject;
	}

	public function setData(?Data $data): static {
		$this->data = $data;
		return $this;
	}
}
