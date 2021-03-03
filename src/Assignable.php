<?php
declare(strict_types = 1);
namespace Lemuria;

interface Assignable
{
	/**
	 * Get the unique ID.
	 */
	public function Uuid(): string;

	/**
	 * Get the creation timestamp.
	 */
	public function Creation(): int;
}
