<?php
declare(strict_types = 1);
namespace Lemuria;

final readonly class NullContainer implements EntityContainer
{
	public function __construct(private bool $contains = true) {
	}

	public function contains(Identifiable $identifiable): bool {
		return $this->contains;
	}

	public function has(Id $id): bool {
		return $this->contains;
	}
}
