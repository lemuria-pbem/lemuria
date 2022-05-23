<?php
declare (strict_types = 1);
namespace Lemuria;

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
	public function __toString(): string;
}
