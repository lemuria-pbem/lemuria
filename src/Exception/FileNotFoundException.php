<?php
declare(strict_types = 1);
namespace Lemuria\Exception;

use JetBrains\PhpStorm\Pure;

class FileNotFoundException extends \RuntimeException
{
	#[Pure] public function __construct(string $file) {
		parent::__construct('File not found: ' . $file);
	}
}
