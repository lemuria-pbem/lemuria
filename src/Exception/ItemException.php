<?php
declare (strict_types = 1);
namespace Lemuria\Exception;

use function Lemuria\getClass;
use Lemuria\ItemAction;
use Lemuria\Item;

/**
 * This exception is thrown by the Item class.
 */
class ItemException extends \InvalidArgumentException
{
	/**
	 * Create an exception for a specific action with two items.
	 */
	public function __construct(Item $item, Item $otherItem, ItemAction $action)
	{
		$class      = getClass($item->getObject());
		$otherCLass = getClass($otherItem->getObject());
		$message    = match ($action) {
			ItemAction::ADD_WRONG_ITEM    => 'A number of ' . $otherCLass . ' cannot be added to a number of ' . $class . '.',
			ItemAction::REMOVE_TOO_MUCH   => 'Cannot remove ' . $otherItem->Count() . ' items from a number of ' . $item->Count() . ' items.',
			ItemAction::REMOVE_WRONG_ITEM => 'A number of ' . $otherCLass . ' cannot be removed from a number of ' . $class . '.'
		};
		parent::__construct($message);
	}
}
