<?php
declare(strict_types = 1);
namespace Lemuria\Tests\Storage;

use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\Attributes\Test;
use SATHub\PHPUnit\Base;

use Lemuria\Storage\FileProvider;

class FileProviderTest extends Base
{
	private const DIR = __DIR__ . '/../storage/root';

	private const FILE = '1.txt';

	#[Test]
	public function construct(): FileProvider {
		$provider = new FileProvider(self::DIR);

		$this->assertInstanceOf(FileProvider::class, $provider);

		return $provider;
	}

	#[Test]
	#[Depends('construct')]
	public function exists(FileProvider $provider): void {
		$this->assertFalse($provider->exists('xyz'));
		$this->assertTrue($provider->exists(self::FILE));
	}

	#[Test]
	#[Depends('construct')]
	public function read(FileProvider $provider): void {
		$this->assertSame('1', $provider->read(self::FILE));
	}
}
