<?php
declare(strict_types = 1);
namespace Lemuria\Exception;

class DirectoryNotFoundException extends FileException
{
	public function __construct(string $directory) {
		parent::__construct('Directory not found: ' . $directory);
	}
}
