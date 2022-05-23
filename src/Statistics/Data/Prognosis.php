<?php
declare(strict_types = 1);
namespace Lemuria\Statistics\Data;

use Lemuria\Statistics\Data;

/**
 * Statistical data that consists of a numerical value and optional change value.
 */
class Prognosis extends Number
{
	public function __construct(int|float $value = 0, int|float|null $change = null, public int $eta = 0) {
		parent::__construct($value, $change);
	}

	public function serialize(): array {
		return [$this->value, $this->change, $this->eta];
	}

	public function unserialize(mixed $data): Data {
		parent::unserialize($data);
		if (is_array($data) && count($data) >= 3) {
			$this->eta = array_key_exists(2, $data) && is_int($data[2]) ? $data[2] : 0;
		}
		return $this;
	}
}
