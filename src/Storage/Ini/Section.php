<?php
declare(strict_types = 1);
namespace Lemuria\Storage\Ini;

use Lemuria\Exception\LemuriaException;

class Section
{
	private readonly string $name;

	private Lines $lines;

	private Values $values;

	public function __construct(string $name) {
		if (str_contains($name, '[') || str_contains($name, ']')) {
			throw new LemuriaException('Invalid name given.');
		}
		$this->name   = trim($name);
		$this->lines  = new Lines();
		$this->values = new Values();
	}

	public function Name(): string {
		return $this->name;
	}

	public function Lines(): Lines {
		return $this->lines;
	}

	public function Values(): Values {
		return $this->values;
	}
}
