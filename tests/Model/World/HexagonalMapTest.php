<?php
declare (strict_types = 1);
namespace Lemuria\Tests\Model\World;

use Lemuria\Exception\UnserializeException;
use Lemuria\Model\World\Geometry;
use Lemuria\Model\World\HexagonalMap;

use Lemuria\Tests\Test;

class HexagonalMapTest extends Test
{
	protected const DATA = [
		'origin'   => ['x' => 0, 'y' => 0],
		'geometry' => Geometry::Flat->value,
		'map'      => [[21, 22, 23, 24, 25], [16, 17, 18, 19, 20], [11, 12, 13, 14, 15], [6, 7, 8, 9, 10], [1, 2, 3, 4, 5]]
	];

	/**
	 * @test
	 */
	public function construct(): HexagonalMap {
		$map = new HexagonalMap();

		$this->assertNotNull($map);

		return $map;
	}

	/**
	 * @test
	 * @depends construct
	 */
	public function unserialize(HexagonalMap $map): HexagonalMap {
		$this->assertSame($map, $map->unserialize(self::DATA));

		return $map;
	}

	/**
	 * @test
	 * @depends unserialize
	 */
	public function serialize(HexagonalMap $map): HexagonalMap {
		$this->assertSame(self::DATA, $map->serialize());

		return $map;
	}

	/**
	 * @test
	 * @depends serialize
	 */
	public function unserializeSphericalWorld(HexagonalMap $map): HexagonalMap {
		$data             = self::DATA;
		$data['geometry'] = Geometry::Spherical->value;

		$this->assertSame($map, $map->unserialize($data));

		return $map;
	}

	/**
	 * @test
	 * @depends unserializeSphericalWorld
	 */
	public function unserializeInvalidGeometry(HexagonalMap $map): void {
		$data             = self::DATA;
		$data['geometry'] = 'invalid';

		$this->expectException(UnserializeException::class);
		$map->unserialize($data);
	}
}
