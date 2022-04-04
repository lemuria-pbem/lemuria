<?php
declare(strict_types = 1);
namespace Lemuria;

use Lemuria\Statistics\Metrics;
use Lemuria\Statistics\Officer;
use Lemuria\Statistics\Record;
use Lemuria\Version\VersionTag;

/**
 * Main statistics class that registers officers, distributes messages and answers compilation requests.
 */
interface Statistics
{
	public function load(): void;

	public function save(): void;

	public function register(Officer $officer): Statistics;

	public function resign(Officer $officer): Statistics;

	public function enqueue(Metrics $message): Statistics;

	public function request(Record $record): Record;

	public function store(Record $record): Statistics;

	public function getVersion(): VersionTag;
}
