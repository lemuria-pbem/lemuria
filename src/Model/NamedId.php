<?php
declare(strict_types = 1);
namespace Lemuria\Model;

use Lemuria\Id;

final class NamedId
{
	public function __construct(private Id $id, private string $name) {
	}

	public function Id(): Id {
		return $this->id;
	}

	public function Name(): string {
		return $this->name;
	}
}
