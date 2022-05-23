<?php
declare (strict_types = 1);
namespace Lemuria;

/**
 * Common implementation of a Singleton.
 */
trait SingletonTrait
{
	/**
	 * Get the class of the singleton.
	 */
	public function __toString(): string {
		return getClass($this);
	}
}
