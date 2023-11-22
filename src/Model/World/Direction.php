<?php
declare(strict_types = 1);
namespace Lemuria\Model\World;

enum Direction : string
{
	public final const self IN_DOCK = self::None;

	public final const self ROUTE_STOP = self::None;

	/**
	 * Get the opposite direction.
	 */
	public function getOpposite(): Direction {
		return match ($this) {
			Direction::Northeast => Direction::Southwest,
			Direction::East      => Direction::West,
			Direction::Southeast => Direction::Northwest,
			Direction::Southwest => Direction::Northeast,
			Direction::West      => Direction::East,
			Direction::Northwest => Direction::Southeast,
			Direction::North     => Direction::South,
			Direction::South     => Direction::North,
			default              => Direction::None
		};
	}

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
