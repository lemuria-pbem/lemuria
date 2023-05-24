<?php
declare (strict_types = 1);
namespace Lemuria\Tests\Model\Calendar;

use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\Attributes\Test;

use Lemuria\Model\Calendar;
use Lemuria\Model\Calendar\BaseCalendar;
use Lemuria\Model\Calendar\Season;

use Lemuria\Tests\Base;

class BaseCalendarTest extends Base
{
	#[Test]
	public function construct(): BaseCalendar {
		$calendar = new BaseCalendar();
		$this->assertInstanceOf(BaseCalendar::class, $calendar);
		return $calendar;
	}

	#[Test]
	#[Depends('construct')]
	public function serialize(BaseCalendar $calendar): void {
		$data = $calendar->serialize();
		$this->assertArray($data, 2);
		$this->assertArrayKey($data, 'round', 0);
		$this->assertArrayKey($data, 'version', '');
	}

	#[Test]
	#[Depends('construct')]
	public function Month(BaseCalendar $calendar): void {
		$this->assertSame(1, $calendar->Month());
	}

	#[Test]
	#[Depends('construct')]
	public function Round(BaseCalendar $calendar): void {
		$this->assertSame(0, $calendar->Round());
	}

	#[Test]
	#[Depends('construct')]
	public function Season(BaseCalendar $calendar): void {
		$this->assertSame(Season::Spring, $calendar->Season());
	}

	#[Test]
	#[Depends('construct')]
	public function Week(BaseCalendar $calendar): void {
		$this->assertSame(1, $calendar->Week());
	}

	#[Test]
	#[Depends('construct')]
	public function Year(BaseCalendar $calendar): void {
		$this->assertSame(1, $calendar->Year());
	}

	#[Test]
	#[Depends('construct')]
	public function nextRound(BaseCalendar $calendar): void {
		/** @noinspection PhpIdempotentOperationInspection */
		$expected = 0 + 1;
		$this->assertSame($expected, $calendar->nextRound());
		$this->assertSame($expected, $calendar->Round());
	}

	#[Test]
	#[Depends('construct')]
	public function getCompatibility(BaseCalendar $calendar): void {
		$this->assertSame('', $calendar->getCompatibility());
	}

	#[Test]
	#[Depends('construct')]
	public function testChangeOfYear(Calendar $calendar): void {
		$round = 24; // last week before year changes
		$data  = ['round' => $round, 'version' => '1.0.0'];
		$this->assertSame($calendar, $calendar->unserialize($data));
		$this->assertSame($round, $calendar->Round());
		$this->assertSame(3, $calendar->Week());
		$this->assertSame(4 * 2, $calendar->Month());
		$this->assertSame(Season::Winter, $calendar->Season());
		$this->assertSame(1, $calendar->Year());

		$this->assertSame($round + 1, $calendar->nextRound());
		$this->assertSame($round + 1, $calendar->Round());
		$this->assertSame(1, $calendar->Week());
		$this->assertSame(1, $calendar->Month());
		$this->assertSame(Season::Spring, $calendar->Season());
		$this->assertSame(2, $calendar->Year());

		$this->assertSame('1.0.0', $calendar->getCompatibility());
	}

	#[Test]
	#[Depends('construct')]
	public function unserialize(BaseCalendar $calendar): void {
		$round = 2 + 6 + 24 ; // second week in third month in year 2
		$data  = ['round' => $round, 'version' => '1.0.0'];
		$this->assertSame($calendar, $calendar->unserialize($data));
		$this->assertSame($round, $calendar->Round());
		$this->assertSame(2, $calendar->Week());
		$this->assertSame(3, $calendar->Month());
		$this->assertSame(Season::Summer, $calendar->Season());
		$this->assertSame(2, $calendar->Year());
		$this->assertSame('1.0.0', $calendar->getCompatibility());
	}
}
