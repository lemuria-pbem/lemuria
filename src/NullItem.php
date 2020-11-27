<?php
declare (strict_types = 1);
namespace Lemuria;

/**
 * A item null object.
 */
final class NullItem extends Item
{
	public function __construct(Singleton|string $singleton) {
		$object = $singleton instanceof Singleton ? $singleton : Lemuria::Builder()->create($singleton);
		parent::__construct($object);
	}
}
