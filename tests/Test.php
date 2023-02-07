<?php
declare (strict_types = 1);
namespace Lemuria\Tests;

use PHPUnit\Framework\TestCase;

use function Lemuria\getClass;

abstract class Test extends TestCase
{
	/**
	 * Assert that the actual value is an array containing an exact number of elements that may have a defined type.
	 */
	public static function assertArray(mixed $actual, int $count = 0, ?string $type = null, string $message = ''): void {
		parent::assertIsArray($actual, $message);
		$message = $message ?? 'Expected array of ' . $count . ' elements.';
		parent::assertSame($count, count($actual), $message);
		if ($type) {
			parent::assertContainsOnly($type, $actual);
		}
	}

	/**
	 * Assert that array has key and value.
	 */
	public static function assertArrayKey(mixed $actual, mixed $key, mixed $value, string $message = ''): void {
		parent::assertIsArray($actual, $message);
		parent::assertArrayHasKey($key, $actual, $message);
		$actualValue = $actual[$key];
		$message     = $message ?? 'Expected array key ' . $key . ' has value ' . $value . ' (actual value is ' . $actualValue . ').';
		parent::assertSame($value, $actualValue, $message);
	}

	/**
	 * Mark a test incomplete.
	 */
	protected function incomplete(string $message = 'is incomplete'): void {
		$message = trim($message, ' .');
		$this->markTestIncomplete('Test ' . getClass($this) . '() ' . $message . '.');
	}
}
