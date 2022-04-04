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

	public static function from(Metrics $metrics): Record {
		return new self($metrics->Subject(), $metrics->Entity());
	}

	public function __construct(private string $key, private ?Identifiable $entity = null) {
	}

	public function Key(): string {
		return $this->key;
	}

	public function Entity(): ?Identifiable {
		return $this->entity;
	}

	public function Data(): ?Data {
		return $this->data;
	}

	public function setData(?Data $data): Record {
		$this->data = $data;
		return $this;
	}
}
