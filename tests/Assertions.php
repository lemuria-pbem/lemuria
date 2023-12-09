<?php
declare (strict_types = 1);
namespace Lemuria\Tests;

use Lemuria\Model\Coordinates;
use Lemuria\Model\Location;
use PHPUnit\Framework\Assert;

use function Lemuria\getClass;
use Lemuria\ItemSet;

trait Assertions
{
	/**
	 * @param array<string, int> $expected
	 */
	public static function assertItemSet(array $expected, ItemSet $actual): void {
		Assert::assertSame(count($expected), $actual->count(), 'Item set count is different.');
		foreach ($expected as $class => $count) {
			Assert::assertTrue($actual->offsetExists($class), 'Item set has no ' . getClass($class) . '.');
			Assert::assertSame($count, $actual[$class]->Count(), 'Item set does not have ' . $count . ' ' . getClass($class) . '.');
		}
		foreach ($actual as $item) {
			$class = get_class($item->getObject());
			Assert::assertTrue(isset($expected[$class]), 'Item set has unexpected ' . $item . '.');
		}
	}

	public static function assertCoordinates(int $x, int $y, mixed $coordinates): void {
		Assert::assertInstanceOf(Coordinates::class, $coordinates);
		Assert::assertSame($x, $coordinates->X(), 'Coordinate x = ' . $x . ' expected.');
		Assert::assertSame($y, $coordinates->Y(), 'Coordinate y = ' . $y . ' expected.');
	}
}
