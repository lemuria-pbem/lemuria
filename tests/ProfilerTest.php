<?php
declare(strict_types = 1);
namespace Lemuria\Tests;

use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\Attributes\Test;
use SATHub\PHPUnit\Base;

use Lemuria\Profiler;
use Lemuria\ProfileRecord;

class ProfilerTest extends Base
{
	private const float ZERO_HOUR = 0.12345;

	private const string IDENTIFIER = 'MyOwnRecord';

	private static float $now;

	#[Test]
	public function construct(): Profiler {
		$profiler = new Profiler();

		$this->assertNotNull($profiler);

		return $profiler;
	}

	#[Test]
	#[Depends('construct')]
	public function isEnabled(Profiler $profiler): Profiler {
		$this->assertTrue($profiler->isEnabled());

		return $profiler;
	}

	#[Test]
	#[Depends('isEnabled')]
	public function setEnabled(Profiler $profiler): void {
		$this->assertSame($profiler, $profiler->setEnabled(false));
		$this->assertFalse($profiler->isEnabled());
	}

	#[Test]
	#[Depends('construct')]
	public function hasZeroHourRecord(Profiler $profiler): Profiler {
		$record = $profiler->getRecord(Profiler::RECORD_ZERO);

		$this->assertInstanceOf(ProfileRecord::class, $record);
		$this->assertGreaterThan(0.0, $record->Timestamp());

		return $profiler;
	}

	#[Test]
	#[Depends('hasZeroHourRecord')]
	public function record(Profiler $profiler): Profiler {
		self::$now = microtime(true);

		$this->assertSame($profiler, $profiler->record(self::IDENTIFIER));

		return $profiler;
	}

	#[Test]
	#[Depends('record')]
	public function getRecord(Profiler $profiler): Profiler {
		$record = $profiler->getRecord(self::IDENTIFIER);

		$this->assertInstanceOf(ProfileRecord::class, $record);
		$this->assertGreaterThan(self::$now, $record->Timestamp());

		return $profiler;
	}

	#[Test]
	public function constructWithHourZero(): Profiler {
		putenv(Profiler::LEMURIA_ZERO_HOUR . '=' . self::ZERO_HOUR);
		$profiler = new Profiler();

		$this->assertNotNull($profiler);

		return $profiler;
	}


	#[Test]
	#[Depends('constructWithHourZero')]
	public function hasCorrectZeroHourRecord(Profiler $profiler): void {
		$record = $profiler->getRecord(Profiler::RECORD_ZERO);

		$this->assertInstanceOf(ProfileRecord::class, $record);
		$this->assertSame(self::ZERO_HOUR, $record->Timestamp());
	}

	#[Test]
	#[Depends('getRecord')]
	public function offsetExists(Profiler $profiler): void {
		$this->assertTrue(isset($profiler[Profiler::RECORD_ZERO]));
		$this->assertTrue(isset($profiler[self::IDENTIFIER]));
		$this->assertFalse(isset($profiler['IDoNotExist']));
	}

	#[Test]
	#[Depends('getRecord')]
	public function offsetGet(Profiler $profiler): void {
		$record = $profiler[self::IDENTIFIER];

		$this->assertInstanceOf(ProfileRecord::class, $record);
		$this->assertGreaterThan(0.0, $record->Timestamp());
	}

	#[Test]
	#[Depends('getRecord')]
	public function countIsCorrect(Profiler $profiler): void {
		$this->assertSame(2, count($profiler));
	}

	#[Test]
	#[Depends('getRecord')]
	public function iteratorRunsCorrect(Profiler $profiler): void {
		$count = 0;
		foreach ($profiler as $identifier => $record) {
			$this->assertIsString($identifier);
			$this->assertInstanceOf(ProfileRecord::class, $record);
			$this->assertGreaterThan(0.0, $record->Timestamp());
			$count++;
		}

		$this->assertSame(2, $count);
	}
}
