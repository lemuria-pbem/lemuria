<?php
declare(strict_types = 1);
namespace Lemuria\Model\World;

enum Direction : string
{
	public final const IN_DOCK = self::NONE;

	public final const ROUTE_STOP = self::NONE;

	case NONE = '';

	case NORTH = 'N';

	case NORTHEAST = 'NE';

	case EAST = 'E';

	case SOUTHEAST = 'SE';

	case SOUTH = 'S';

	case SOUTHWEST = 'SW';

	case WEST = 'W';

	case NORTHWEST = 'NW';
}
