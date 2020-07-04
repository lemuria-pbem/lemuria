<?php
declare (strict_types = 1);
namespace Lemuria;

use Lemuria\Exception\SingletonException;

/**
 * A builder creates singleton objects.
 */
interface Builder
{
	/**
	 * Create a singleton.
	 *
	 * This is a convenient wrapper for create().
	 *
	 * @param string $class
	 * @return Singleton
	 * @throws SingletonException
	 */
	public function create(string $class): Singleton;
}
