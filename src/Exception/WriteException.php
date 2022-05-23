<?php
declare(strict_types = 1);
namespace Lemuria\Exception;

class WriteException extends FileException
{
	public function __construct(string $path) {
		parent::__construct('File write error: ' . $path);
	}
}
