<?php
declare(strict_types = 1);
namespace Lemuria;

enum ItemAction : int
{
	case ADD_WRONG_ITEM = 1;

	case REMOVE_TOO_MUCH = 0;

	case REMOVE_WRONG_ITEM = -1;
}
