<?php
declare (strict_types = 1);
namespace Lemuria\Tests\Model\World\Strategy;

use PHPUnit\Framework\Attributes\Test;
use SATHub\PHPUnit\Base;

use Lemuria\Model\Location;
use Lemuria\Model\World\Direction;
use Lemuria\Model\World\Geometry;
use Lemuria\Model\World\HexagonalMap;
use Lemuria\Model\World\Path;
use Lemuria\Model\World\Strategy\ShortestPath;
use Lemuria\Model\World\Way;

use Lemuria\Tests\Assertions;
use Lemuria\Tests\Mock\Model\LocationMock;

class ShortestPathTest extends Base
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

	protected function getSut(Geometry $geometry): ShortestPath {
		$data             = self::DATA;
		$data['geometry'] = $geometry->value;
		$data['map']      = array_reverse($data['map']);
		$map              = new HexagonalMap();
		return new ShortestPath($map->unserialize($data));
	}

	#[Test]
	public function findPathOfZeroLength(): void {
		$strategy = $this->getSut(Geometry::Flat);

		$this->assertSame($strategy, $strategy->find(new LocationMock(24), new LocationMock(24)));
		$this->assertTrue($strategy->isViable());

		$path = $strategy->getAll();

		$this->assertInstanceOf(Path::class, $path);
		$this->assertSame(1, $path->count());

		$way = $path[0];

		$this->assertInstanceOf(Way::class, $way);
		$this->assertSame(1, $way->count());
		foreach ($way as $direction => $location) {
			$this->assertSame(Direction::None, $direction);
			$this->assertInstanceOf(Location::class, $location);
			$this->assertSame(24, $location->Id()->Id());
		}
		$this->assertSame($way, $strategy->getBest());
	}

	#[Test]
	public function findPathOfLengthOne(): void {
		$this->markTestSkipped('This test needs a full Lemuria model.');

		$strategy = $this->getSut(Geometry::Flat);

		$this->assertSame($strategy, $strategy->find(new LocationMock(24), new LocationMock(34)));
		$this->assertTrue($strategy->isViable());

		$path = $strategy->getAll();

		$this->assertInstanceOf(Path::class, $path);
		$this->assertSame(1, $path->count());

		$way      = $path[0];
		$i        = 0;
		$expected = [[24 => Direction::None], [34 => Direction::Northeast]];

		$this->assertInstanceOf(Way::class, $way);
		$this->assertSame(2, $way->count());
		foreach ($way as $direction => $location) {
			$this->assertSame(current($expected[$i]), $direction);
			$this->assertInstanceOf(Location::class, $location);
			$this->assertSame(key($expected[$i++]), $location->Id()->Id());
		}
		$this->assertSame($way, $strategy->getBest());
	}
}
