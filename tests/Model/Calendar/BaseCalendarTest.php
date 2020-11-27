<?php
declare (strict_types = 1);
namespace Lemuria\Tests\Model\Calendar;

use Lemuria\Model\Calendar;
use Lemuria\Model\Calendar\BaseCalendar;
use Lemuria\Tests\Test;

class BaseCalendarTest extends Test
{
	/**
	 * @test
	 */
	public function construct(): BaseCalendar {
		$calendar = new BaseCalendar();
		$this->assertInstanceOf(BaseCalendar::class, $calendar);
		return $calendar;
	}

	/**
	 * @test
	 * @depends construct
	 */
	public function serialize(BaseCalendar $calendar): void {
		$data = $calendar->serialize();
		$this->assertArray($data, 1, 'int');
		$this->assertArrayKey($data, 'round', 0);
	}

	/**
	 * @test
	 * @depends construct
	 */
	public function Month(BaseCalendar $calendar): void {
		$this->assertSame(1, $calendar->Month());
	}

	/**
	 * @test
	 * @depends construct
	 */
	public function Round(BaseCalendar $calendar): void {
		$this->assertSame(0 + 1, $calendar->Round());
	}

	/**
	 * @test
	 * @depends construct
	 */
	public function Season(BaseCalendar $calendar): void {
		$this->assertSame(1, $calendar->Season());
	}

	/**
	 * @test
	 * @depends construct
	 */
	public function Week(BaseCalendar $calendar): void {
		$this->assertSame(1, $calendar->Week());
	}

	/**
	 * @test
	 * @depends construct
	 */
	public function Year(BaseCalendar $calendar): void {
		$this->assertSame(1, $calendar->Year());
	}

	/**
	 * @test
	 * @depends construct
	 */
	public function nextRound(BaseCalendar $calendar): void {
		$expected = 0 + 1 + 1;
		$this->assertSame($expected, $calendar->nextRound());
		$this->assertSame($expected, $calendar->Round());
	}

	/**
	 * @test
	 * @depends construct
	 */
	public function testChangeOfYear(Calendar $calendar): void {
		$round = 24; // letzte Woche vor Jahreswechsel
		$data  = ['round' => $round - 1];
		$this->assertSame($calendar, $calendar->unserialize($data));
		$this->assertSame($round, $calendar->Round());
		$this->assertSame(3, $calendar->Week());
		$this->assertSame(4 * 2, $calendar->Month());
		$this->assertSame(4, $calendar->Season());
		$this->assertSame(1, $calendar->Year());

		$this->assertSame($round + 1, $calendar->nextRound());
		$this->assertSame($round + 1, $calendar->Round());
		$this->assertSame(1, $calendar->Week());
		$this->assertSame(1, $calendar->Month());
		$this->assertSame(1, $calendar->Season());
		$this->assertSame(2, $calendar->Year());
	}

	/**
	 * @test
	 * @depends construct
	 */
	public function unserialize(BaseCalendar $calendar): void {
		$round = 1 + 24 + 9 + 1; // zweite Woche im dritten Monat in Jahr 2
		$data  = ['round' => $round - 1];
		$this->assertSame($calendar, $calendar->unserialize($data));
		$this->assertSame($round, $calendar->Round());
		$this->assertSame(2, $calendar->Week());
		$this->assertSame(3, $calendar->Month());
		$this->assertSame(2, $calendar->Season());
		$this->assertSame(2, $calendar->Year());
	}
}
