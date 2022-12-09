<?php
declare(strict_types = 1);
namespace Lemuria;

enum ItemAction : int
{
	case AddWrongItem = 1;

	case RemoveTooMuch = 0;

	case RemoveWrongItem = -1;
}
