<?php
declare (strict_types = 1);
namespace Lemuria\Tests;

use PHPUnit\Framework\TestCase;

abstract class Test extends TestCase
{
	/**
	 * Assert that the actual value is an array containing an exact number of elements that may have a defined type.
	 *
	 * @param mixed $actual The expected array.
	 * @param int|null $count The expected number of elements.
	 * @param string|null $type The expected type of the array's elements.
	 * @param string|null $message
	 */
	public static function assertArray($actual, int $count = 0, string $type = null, string $message = ''): void {
		parent::assertIsArray($actual, $message);
		$message = $message ?? 'Expected array of ' . $count . ' elements.';
		parent::assertSame($count, count($actual), $message);
		if ($type) {
			parent::assertContainsOnly($type, $actual);
		}
	}

	/**
	 * Assert that array has key and value.
	 *
	 * @param array $actual
	 * @param mixed $key
	 * @param mixed $value
	 * @param string $message
	 */
	public static function assertArrayKey($actual, $key, $value, string $message = ''): void {
		parent::assertIsArray($actual, $message);
		parent::assertArrayHasKey($key, $actual, $message);
		$actualValue = $actual[$key];
		$message     = $message ?? 'Expected array key ' . $key . ' has value ' . $value . ' (actual value is ' . $actualValue . ').';
		parent::assertSame($value, $actualValue, $message);
	}

	/**
	 * Load Lenuria functions.
	 */
	protected function setUp(): void {
		parent::setUp();
		require_once __DIR__ . '/../src/Lemuria.php';
	}

	/**
	 * Mark a test incomplete.
	 *
	 * @param string $message
	 */
	protected function incomplete($message = 'is incomplete') {
		$message = trim($message, ' .');
		$this->markTestIncomplete('Test ' . $this->getName() . '() ' . $message . '.');
	}
}
