<?php
declare(strict_types = 1);
namespace Lemuria\Statistics;

/**
 * Represents statistical data.
 */
interface Data
{
	public function serialize(): mixed;

	public function unserialize(mixed $data): Data;
}
