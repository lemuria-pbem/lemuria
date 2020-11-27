<?php
declare (strict_types = 1);
namespace Lemuria\Model;

use Lemuria\Serializable;

/**
 * Coordinates define the two-dimensional location on a map of Lemuria.
 */
interface Coordinates extends Serializable
{
	public function X(): int;

	public function Y(): int;

	public function __toString(): string;
}
