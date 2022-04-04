<?php
declare(strict_types = 1);
namespace Lemuria\Statistics;

use Lemuria\Identifiable;

/**
 * Represents statistical data records.
 */
interface Record
{
	public function Key(): string;

	public function Identifiable(): ?Identifiable;

	public function Data(): ?Data;
}
