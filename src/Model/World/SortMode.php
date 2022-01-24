<?php
declare(strict_types = 1);
namespace Lemuria\Model\World;

enum SortMode
{
	case BY_ID;

	case BY_RESIDENTS;

	case NORTH_TO_SOUTH;
}
