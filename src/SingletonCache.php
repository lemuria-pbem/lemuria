<?php
declare (strict_types = 1);
namespace Lemuria;

/**
 * The Singleton cache implementation is a simple key/value store in volatile memory.
 */
class SingletonCache
{
	/**
	 * @var array(string=>Singleton)
	 */
	private array $cache = [];

	/**
	 * Get the singleton of a given class.
	 *
	 * @param string $class
	 * @return Singleton|null
	 */
	public function get(string $class): ?Singleton {
		return $this->cache[$class] ?? null;
	}

	/**
	 * Set the singleton of a given class.
	 *
	 * @param Singleton $object
	 * @return Singleton
	 */
	public function set(Singleton $object): Singleton {
		$class               = getClass($object);
		$this->cache[$class] = $object;

		return $object;
	}
}
