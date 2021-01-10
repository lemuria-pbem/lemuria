<?php
declare(strict_types = 1);
namespace Lemuria\Model\Calendar;

/**
 * A moment is the date of an event in the calendar.
 */
final class Moment extends BaseCalendar
{
	public function __construct(int $round) {
		$this->setRound(--$round);
	}
}
