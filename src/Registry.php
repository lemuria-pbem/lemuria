<?php
declare(strict_types = 1);
namespace Lemuria;

interface Registry extends \Countable
{
	public function find(string $uuid): ?Assignable;
}
