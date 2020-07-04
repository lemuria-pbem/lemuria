<?php
declare (strict_types = 1);
namespace Lemuria;

/**
 * A item null object.
 */
final class NullItem extends Item
{
	/**
	 * Create an empty item.
	 *
	 * @param Singleton|string $singleton
	 */
	public function __construct($singleton) {
		$object = $singleton instanceof Singleton ? $singleton : Lemuria::Builder()->create($singleton);
		parent::__construct($object);
	}
}
