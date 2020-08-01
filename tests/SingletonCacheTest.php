<?php
declare(strict_types = 1);
namespace Lemuria\Tests;

use Lemuria\Factory\SingletonCache;

use Lemuria\Tests\Mock\SingletonMock;

class SingletonCacheTest extends Test
{
	/**
	 * @test
	 * @return SingletonCache
	 */
	public function construct(): SingletonCache {
		$cache = new SingletonCache();

		$this->assertNotNull($cache);

		return $cache;
	}

	/**
	 * @test
	 * @depends construct
	 * @param SingletonCache $cache
	 */
	public function getInitial(SingletonCache $cache): void {
		$this->assertNull($cache->get('SingletonMock'));
	}

	/**
	 * @test
	 * @depends construct
	 * @param SingletonCache $cache
	 * @return SingletonCache
	 */
	public function setFirstTime(SingletonCache $cache): SingletonCache {
		$mock = new SingletonMock();

		$this->assertSame($mock, $cache->set($mock));
		$this->assertSame($mock, $cache->get('SingletonMock'));

		return $cache;
	}

	/**
	 * @test
	 * @depends setFirstTime
	 * @param SingletonCache $cache
	 */
	public function setSecondTime(SingletonCache $cache): void {
		$previous = $cache->get('SingletonMock');

		$this->assertInstanceOf(SingletonMock::class, $previous);

		$mock = new SingletonMock();

		$this->assertSame($mock, $cache->set($mock));
		$this->assertSame($mock, $cache->get('SingletonMock'));
	}
}
