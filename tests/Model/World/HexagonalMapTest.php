<?php
declare (strict_types = 1);
namespace Lemuria\Tests\Model\World;

use Lemuria\Model\World\Direction;
use Lemuria\Model\World\Way;
use PHPUnit\Framework\Attributes\Test;
use SATHub\PHPUnit\Base;

use Lemuria\Model\World\Geometry;
use Lemuria\Model\World\HexagonalMap;
use Lemuria\Model\World\Strategy\ShortestPath;

use Lemuria\Tests\Assertions;
use Lemuria\Tests\Mock\Model\LocationMock;

class HexagonalMapTest extends Base
{
	use Assertions;

	/**
	 * @type array<string, mixed>
	 */
	protected const array DATA = [
		'origin'   => ['x' => 0, 'y' => 0],
		'geometry' => Geometry::Flat->value,
		'map'      => [
			[                  91, 92, 93, 94, 95, 96, 97, 98, 99, 100],
			[                81, 82, 83, 84, 85, 86, 87, 88, 89, 90   ],
			[              71, 72, 73, 74, 75, 76, 77, 78, 79, 80     ],
			[            61, 62, 63, 64, 65, 66, 67, 68, 69, 70       ],
			[          51, 52, 53, 54, 55, 56, 57, 58, 59, 60         ],
			[        41, 42, 43, 44, 45, 46, 47, 48, 49, 50           ],
			[      31, 32, 33, 34, 35, 36, 37, 38, 39, 40             ],
			[    21, 22, 23, 24, 25, 26, 27, 28, 29, 30               ],
			[  11, 12, 13, 14, 15, 16, 17, 18, 19, 20                 ],
			[ 1,  2,  3,  4,  5,  6,  7,  8,  9, 10                   ]
		]
	];

	protected function getSut(Geometry $geometry): HexagonalMap {
		$data             = self::DATA;
		$data['geometry'] = $geometry->value;
		$data['map']      = array_reverse($data['map']);
		$map              = new HexagonalMap();
		return $map->unserialize($data);
	}

	#[Test]
	public function getCoordinates(): void {
		$map = $this->getSut(Geometry::Flat);

		$this->assertCoordinates(3, 2, $map->getCoordinates(new LocationMock(24)));
	}

	#[Test]
	public function getDistanceInFlatWorld(): void {
		$map = $this->getSut(Geometry::Flat);

		$this->assertSame(5 + 5, $map->getDistance(new LocationMock(24), new LocationMock(79)));
		$this->assertSame(5 + 5, $map->getDistance(new LocationMock(79), new LocationMock(24)));
	}

	#[Test]
	public function getDistanceInSphericalWorld(): void {
		$map = $this->getSut(Geometry::Spherical);

		$this->assertSame(1 + 3, $map->getDistance(new LocationMock(14), new LocationMock(83)));
		$this->assertSame(1 + 3, $map->getDistance(new LocationMock(83), new LocationMock(14)));
	}

	#[Test]
	public function findPath(): void {
		$map      = $this->getSut(Geometry::Flat);
		$strategy = $map->findPath(new LocationMock(24), new LocationMock(24), ShortestPath::class);

		$this->assertInstanceOf(ShortestPath::class, $strategy);
		$this->assertTrue($strategy->isViable());
	}

	#[Test]
	public function getDirection(): void {
		$map = $this->getSut(Geometry::Flat);
		$way = new Way();
		$way[Direction::None]      = new LocationMock(1);
		$way[Direction::Northwest] = new LocationMock(2);
		$this->assertSame(Direction::Northwest, $map->getDirection($way));

		$way[Direction::Southwest] = new LocationMock(3);
		$this->assertSame(Direction::West, $map->getDirection($way));

		$way[Direction::Southeast] = new LocationMock(4);
		$this->assertSame(Direction::Southwest, $map->getDirection($way));

		$way[Direction::East] = new LocationMock(5);
		$this->assertSame(Direction::Southeast, $map->getDirection($way));

		$way[Direction::Northeast] = new LocationMock(6);
		$this->assertSame(Direction::East, $map->getDirection($way));
	}
}
