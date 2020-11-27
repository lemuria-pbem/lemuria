<?php
declare (strict_types = 1);
namespace Lemuria;

use JetBrains\PhpStorm\Pure;

/**
 * Common implementation of a Singleton.
 */
trait SingletonTrait
{
	/**
	 * Get the class of the singleton.
	 */
	#[Pure] public function __toString(): string {
		return getClass($this);
	}
}
