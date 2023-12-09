<?php
declare (strict_types = 1);
namespace Lemuria\Tests\Model\World;

use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\Attributes\Test;
use SATHub\PHPUnit\Base;

use Lemuria\Exception\UnserializeException;
use Lemuria\Model\World\BaseMap;
use Lemuria\Model\World\Geometry;
use Lemuria\Model\World\OctagonalMap;

class BaseMapTest extends Base
{
	/**
	 * @type array<string, mixed>
	 */
	protected const array DATA = [
		'origin'   => ['x' => 0, 'y' => 0],
		'geometry' => Geometry::Flat->value,
		'map'      => [
			[91, 92, 93, 94, 95, 96, 97, 98, 99, 100],
			[81, 82, 83, 84, 85, 86, 87, 88, 89, 90],
			[71, 72, 73, 74, 75, 76, 77, 78, 79, 80],
			[61, 62, 63, 64, 65, 66, 67, 68, 69, 70],
			[51, 52, 53, 54, 55, 56, 57, 58, 59, 60],
			[41, 42, 43, 44, 45, 46, 47, 48, 49, 50],
			[31, 32, 33, 34, 35, 36, 37, 38, 39, 40],
			[21, 22, 23, 24, 25, 26, 27, 28, 29, 30],
			[11, 12, 13, 14, 15, 16, 17, 18, 19, 20],
			[ 1,  2,  3,  4,  5,  6,  7,  8,  9, 10]
		]
	];

	#[Test]
	public function construct(): BaseMap {
		$map = new OctagonalMap();

		$this->pass();

		return $map;
	}

	#[Test]
	#[Depends('construct')]
	public function unserialize(BaseMap $map): BaseMap {
		$this->assertSame($map, $map->unserialize(self::DATA));

		return $map;
	}

	#[Test]
	#[Depends('unserialize')]
	public function serialize(BaseMap $map): BaseMap {
		$this->assertSame(self::DATA, $map->serialize());

		return $map;
	}

	#[Test]
	#[Depends('serialize')]
	public function unserializeSphericalWorld(BaseMap $map): BaseMap {
		$data             = self::DATA;
		$data['geometry'] = Geometry::Spherical->value;

		$this->assertSame($map, $map->unserialize($data));

		return $map;
	}

	#[Test]
	#[Depends('unserializeSphericalWorld')]
	public function unserializeInvalidGeometry(BaseMap $map): void {
		$data             = self::DATA;
		$data['geometry'] = 'invalid';

		$this->expectException(UnserializeException::class);

		$map->unserialize($data);
	}
}
