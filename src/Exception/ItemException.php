<?php
declare (strict_types = 1);
namespace Lemuria\Exception;

use JetBrains\PhpStorm\ExpectedValues;
use JetBrains\PhpStorm\Pure;

use function Lemuria\getClass;
use Lemuria\Item;

/**
 * This exception is thrown by the Item class.
 */
class ItemException extends \InvalidArgumentException
{
	public const ADD_WRONG_ITEM = 1;

	public const REMOVE_TOO_MUCH = 0;

	public const REMOVE_WRONG_ITEM = -1;

	/**
	 * Create an exception for a specific action with two items.
	 */
	#[Pure] public function __construct(Item $item, Item $otherItem,
										#[ExpectedValues(valuesFromClass: self::class)] int $action)
	{
		$class      = getClass($item->getObject());
		$otherCLass = getClass($otherItem->getObject());
		$message    = match ($action) {
			self::ADD_WRONG_ITEM    => 'A number of ' . $otherCLass . ' cannot be added to a number of ' . $class . '.',
			self::REMOVE_TOO_MUCH   => 'Cannot remove ' . $otherItem->Count() . ' items from a number of ' . $item->Count() . ' items.',
			self::REMOVE_WRONG_ITEM => 'A number of ' . $otherCLass . ' cannot be removed from a number of ' . $class . '.',
			default                 => '',
		};
		parent::__construct($message);
	}
}
