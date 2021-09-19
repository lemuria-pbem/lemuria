<?php
declare (strict_types = 1);
namespace Lemuria;

use JetBrains\PhpStorm\Pure;

/**
 * A class that implements this Singleton is guaranteed to have only one instance.
 *
 * Two objects of such a Singleton class are identical to each other.
 */
interface Singleton extends \Stringable
{
	/**
	 * Get the class of the singleton.
	 */
	#[Pure] public function __toString(): string;
}
