<?php
declare(strict_types = 1);
namespace Lemuria\Exception;

use JetBrains\PhpStorm\Pure;

class WriteException extends FileException
{
	#[Pure] public function __construct(string $path) {
		parent::__construct('File write error: ' . $path);
	}
}
