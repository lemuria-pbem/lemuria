<?php
declare(strict_types = 1);
namespace Lemuria\Exception;

use JetBrains\PhpStorm\Pure;

class DirectoryNotFoundException extends FileException
{
	#[Pure] public function __construct(string $directory) {
		parent::__construct('Directory not found: ' . $directory);
	}
}
