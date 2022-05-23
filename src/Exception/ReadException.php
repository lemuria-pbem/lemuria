<?php
declare(strict_types = 1);
namespace Lemuria\Exception;

class ReadException extends FileException
{
	public function __construct(string $path) {
		parent::__construct('File read error: ' . $path);
	}
}
