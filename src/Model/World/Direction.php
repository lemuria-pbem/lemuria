<?php
declare(strict_types = 1);
namespace Lemuria\Model\World;

enum Direction : string
{
	public final const IN_DOCK = self::None;

	public final const ROUTE_STOP = self::None;

	case None = '';

	case North = 'N';

	case Northeast = 'NE';

	case East = 'E';

	case Southeast = 'SE';

	case South = 'S';

	case Southwest = 'SW';

	case West = 'W';

	case Northwest = 'NW';
}
