<?php
declare(strict_types = 1);
namespace Lemuria\Statistics\Data;

use Lemuria\Exception\UnserializeException;
use Lemuria\Statistics\Data;

/**
 * Statistical data that consists of a numerical value and optional change value.
 */
class Number implements Data
{
	public function __construct(public int|float $value = 0, public int|float|null $change = null) {
	}

	public function serialize(): array {
		return [$this->value, $this->change];
	}

	public function unserialize(mixed $data): static {
		if (is_array($data) && count($data) >= 2) {
			if (array_key_exists(0, $data) && is_numeric(($data[0]))) {
				$this->value = $data[0];
				if (array_key_exists(1, $data) && is_numeric($data[1]) || is_null($data[1])) {
					$this->change = $data[1];
				}
				return $this;
			}
		}
		throw new UnserializeException();
	}
}
