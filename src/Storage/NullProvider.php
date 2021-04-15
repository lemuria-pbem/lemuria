<?php
declare(strict_types = 1);
namespace Lemuria\Storage;

class NullProvider implements Provider
{
	public function __construct(string $directory) {
	}

	public function exists(string $fileName): bool {
		return true;
	}

	public function read(string $fileName): mixed {
		return null;
	}

	public function write(string $fileName, mixed $content): void {
	}
}
