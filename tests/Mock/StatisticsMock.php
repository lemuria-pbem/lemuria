<?php
declare(strict_types = 1);
namespace Lemuria\Tests\Mock;

use Lemuria\Statistics;
use Lemuria\Statistics\Metrics;
use Lemuria\Statistics\Officer;
use Lemuria\Statistics\Record;
use Lemuria\Version\VersionFinder;
use Lemuria\Version\VersionTag;

class StatisticsMock implements Statistics
{
	public function load(): void {
	}

	public function save(): void {
	}

	public function register(Officer $officer): Statistics {
		return $this;
	}

	public function resign(Officer $officer): Statistics {
		return $this;
	}

	public function enqueue(Metrics $message): Statistics {
		return $this;
	}

	public function request(Record $record): Record {
		return $record;
	}

	public function store(Record $record): Statistics {
		return $this;
	}

	public function getVersion(): VersionTag {
		$versionFinder = new VersionFinder(__DIR__ . '/../..');
		return $versionFinder->get();
	}
}
