<?php
declare(strict_types = 1);
namespace Lemuria\Exception;

use JetBrains\PhpStorm\Pure;

class ReadException extends FileException
{
	#[Pure] public function __construct(string $path) {
		parent::__construct('File read error: ' . $path);
	}
}
