<?php
declare (strict_types = 1);
namespace Lemuria\Exception;

use function Lemuria\getClass;
use Lemuria\Item;

/**
 * This exception is thrown by the Item class.
 */
class ItemException extends \InvalidArgumentException {

	public const ADD_WRONG_ITEM = 1;

	public const REMOVE_TOO_MUCH = 0;

	public const REMOVE_WRONG_ITEM = -1;

	/**
	 * Create an exception for a specific action with two items.
	 *
	 * @param Item $item
	 * @param Item $otherItem
	 * @param int $action
	 */
	public function __construct(Item $item, Item $otherItem, int $action) {
		$class      = getClass($item->getObject());
		$otherCLass = getClass($otherItem->getObject());
		switch ($action) {
			case self::ADD_WRONG_ITEM :
				$message = 'A number of ' . $otherCLass . ' cannot be added to a number of ' . $class . '.';
				break;
			case self::REMOVE_TOO_MUCH :
				$message = 'Cannot remove ' . $otherItem->Count() . ' items from a number of ' . $item->Count() . ' items.';
				break;
			case self::REMOVE_WRONG_ITEM :
				$message = 'A number of ' . $otherCLass . ' cannot be removed from a number of ' . $class . '.';
				break;
			default :
				$message = '';
		}
		parent::__construct($message);
	}
}
