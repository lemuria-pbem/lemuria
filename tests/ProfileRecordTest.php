<?php
declare(strict_types = 1);
namespace Lemuria\Tests;

use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\Attributes\Test;
use SATHub\PHPUnit\Base;

use Lemuria\ProfileRecord;

class ProfileRecordTest extends Base
{
	#[Test]
	public function construct(): ProfileRecord {
		$record = new ProfileRecord(microtime(true));

		$this->assertNotNull($record);

		return $record;
	}

	#[Test]
	#[Depends('construct')]
	public function toStringFormatsMemory(ProfileRecord $record): void {
		$output = (string)$record;

		$this->assertIsString($output);
	}
}
