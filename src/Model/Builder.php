<?php
declare (strict_types = 1);
namespace Lemuria\Model;

use Lemuria\Exception\SingletonException;
use Lemuria\Factory\SingletonCatalog;
use Lemuria\Singleton;

/**
 * A builder creates singleton objects.
 */
interface Builder
{
	/**
	 * Create a singleton.
	 *
	 * @param string $class
	 * @return Singleton
	 * @throws SingletonException
	 */
	public function create(string $class): Singleton;

	/**
	 * @param SingletonCatalog $catalog
	 * @return Builder
	 */
	public function register(SingletonCatalog $catalog): Builder;
}
