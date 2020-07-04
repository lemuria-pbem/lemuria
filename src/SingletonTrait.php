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
	 *
	 * @return string
	 */
	public function __toString(): string {
		return getClass($this);
	}
}
