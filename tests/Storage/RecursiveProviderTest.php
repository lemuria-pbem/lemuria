<?php
declare(strict_types = 1);
namespace Lemuria\Tests\Storage;

use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\Attributes\Test;
use SATHub\PHPUnit\Base;

use Lemuria\Storage\RecursiveProvider;

class RecursiveProviderTest extends Base
{
	private const string DIR = __DIR__ . '/../storage/root';

	#[Test]
	public function construct(): RecursiveProvider {
		$provider = new RecursiveProvider(self::DIR);

		$this->assertInstanceOf(RecursiveProvider::class, $provider);

		return $provider;
	}

	#[Test]
	#[Depends('construct')]
	public function glob(RecursiveProvider $provider): void {
		$files = $provider->glob('*.txt');

		$this->assertArray($files, 3, 'string');
		foreach ($files as $path) {
			$this->assertStringStartsWith(realpath(self::DIR), $path);
			$this->assertStringEndsWith('.txt', $path);
		}
		$this->assertSame('1.txt', basename($files[0]));
		$this->assertSame('4.txt', basename($files[1]));
		$this->assertSame('5.txt', basename($files[2]));
	}
}
