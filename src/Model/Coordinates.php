<?php
declare (strict_types = 1);
namespace Lemuria\Model;

use JetBrains\PhpStorm\Pure;

use Lemuria\Serializable;

/**
 * Coordinates define the two-dimensional location on a map of Lemuria.
 */
interface Coordinates extends \Stringable, Serializable
{
	#[Pure] public function X(): int;

	#[Pure] public function Y(): int;

	#[Pure] public function __toString(): string;
}
