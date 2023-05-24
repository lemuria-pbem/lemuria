<?php
declare(strict_types = 1);
namespace Lemuria\Tests;

use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\Attributes\Test;

use Lemuria\Factory\SingletonCache;

use Lemuria\Tests\Mock\SingletonMock;

class SingletonCacheTest extends Base
{
	#[Test]
	public function construct(): SingletonCache {
		$cache = new SingletonCache();

		$this->assertNotNull($cache);

		return $cache;
	}

	#[Test]
	#[Depends('construct')]
	public function getInitial(SingletonCache $cache): void {
		$this->assertNull($cache->get('SingletonMock'));
	}

	#[Test]
	#[Depends('construct')]
	public function setFirstTime(SingletonCache $cache): SingletonCache {
		$mock = new SingletonMock();

		$this->assertSame($mock, $cache->set($mock));
		$this->assertSame($mock, $cache->get('SingletonMock'));

		return $cache;
	}

	#[Test]
	#[Depends('setFirstTime')]
	public function setSecondTime(SingletonCache $cache): void {
		$previous = $cache->get('SingletonMock');

		$this->assertInstanceOf(SingletonMock::class, $previous);

		$mock = new SingletonMock();

		$this->assertSame($mock, $cache->set($mock));
		$this->assertSame($mock, $cache->get('SingletonMock'));
	}
}
