<?php
declare (strict_types = 1);
namespace Lemuria\Factory;

use function Lemuria\getClass;
use Lemuria\Singleton;

/**
 * The Singleton cache implementation is a simple key/value store in volatile memory.
 */
class SingletonCache
{
	/**
	 * @var array<string, Singleton>
	 */
	private array $cache = [];

	public function get(string $class): ?Singleton {
		return $this->cache[$class] ?? null;
	}

	public function set(Singleton $object): Singleton {
		$class               = getClass($object);
		$this->cache[$class] = $object;

		return $object;
	}
}
