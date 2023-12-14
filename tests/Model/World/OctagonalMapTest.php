<?php
declare (strict_types = 1);
namespace Lemuria\Tests\Model\World;

use PHPUnit\Framework\Attributes\Test;
use SATHub\PHPUnit\Base;

use Lemuria\Model\World\Geometry;
use Lemuria\Model\World\OctagonalMap;
use Lemuria\Model\World\Strategy\ShortestPath;

use Lemuria\Tests\Assertions;
use Lemuria\Tests\Mock\Model\LocationMock;

class OctagonalMapTest extends Base
{
	use Assertions;

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

	protected function getSut(Geometry $geometry): OctagonalMap {
		$data             = self::DATA;
		$data['geometry'] = $geometry->value;
		$data['map']      = array_reverse($data['map']);
		$map              = new OctagonalMap();
		$map->unserialize($data);
		return $map;
	}

	#[Test]
	public function getCoordinates(): void {
		$map = $this->getSut(Geometry::Flat);

		$this->assertCoordinates(4, 3, $map->getCoordinates(new LocationMock(35)));
	}

	#[Test]
	public function getDistanceInFlatWorld(): void {
		$map = $this->getSut(Geometry::Flat);

		$this->assertSame(2 + 5, $map->getDistance(new LocationMock(16), new LocationMock(88)));
		$this->assertSame(2 + 5, $map->getDistance(new LocationMock(88), new LocationMock(16)));
	}

	#[Test]
	public function getDistanceInSphericalWorld(): void {
		$map = $this->getSut(Geometry::Spherical);

		$this->assertSame(2 + 1, $map->getDistance(new LocationMock(16), new LocationMock(88)));
		$this->assertSame(2 + 1, $map->getDistance(new LocationMock(88), new LocationMock(16)));
	}

	#[Test]
	public function findPath(): void {
		$map      = $this->getSut(Geometry::Flat);
		$strategy = $map->findPath(new LocationMock(24), new LocationMock(24), ShortestPath::class);

		$this->assertInstanceOf(ShortestPath::class, $strategy);
		$this->assertTrue($strategy->isViable());
	}
}
