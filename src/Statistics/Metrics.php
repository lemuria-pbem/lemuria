<?php
declare(strict_types = 1);

namespace Lemuria\Statistics;

use Lemuria\Identifiable;

/**
 * The message to handle by officers.
 */
interface Metrics
{
	public function Entity(): ?Identifiable;

	public function Subject(): string;

	public function Data(): ?Data;
}
