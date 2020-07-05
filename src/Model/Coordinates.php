<?php
declare (strict_types = 1);
namespace Lemuria\Model;

use Lemuria\Serializable;

/**
 * Coordinates define the two-dimensional location on a map of Lemuria.
 */
interface Coordinates extends Serializable
{
	/**
	 * Get the x coordinate.
	 *
	 * @return int
	 */
	public function X(): int;

	/**
	 * Get the y coordinate.
	 *
	 * @return int
	 */
	public function Y(): int;

	/**
	 * Get a string representation.
	 *
	 * @return string
	 */
	public function __toString(): string;
}
