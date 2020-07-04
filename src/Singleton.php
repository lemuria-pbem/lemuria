<?php
declare (strict_types = 1);
namespace Lemuria;

/**
 * A class that implements this Singleton is guaranteed to have only one instance.
 *
 * Two objects of such a Singleton class are identical to each other.
 */
interface Singleton
{
	/**
	 * Get the class of the singleton.
	 *
	 * @return string
	 */
	public function __toString(): string;
}
