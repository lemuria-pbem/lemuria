<?php
declare(strict_types = 1);
namespace Lemuria\Exception;

class FileNotFoundException extends FileException
{
	public function __construct(string $file) {
		parent::__construct('File not found: ' . $file);
	}
}
