<?php
declare(strict_types = 1);
namespace Lemuria\Model;

use Lemuria\Exception\LemuriaException;

#[\AllowDynamicProperties]
class Property
{
	public function __get(string $name): mixed {
		if (!isset($this->$name)) {
			throw new LemuriaException();
		}
		return $this->$name;
	}

	public function __set(string $name, mixed $value): void {
		$this->$name = $value;
	}
}
