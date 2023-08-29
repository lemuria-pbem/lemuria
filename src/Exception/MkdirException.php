<?php
declare(strict_types = 1);
namespace Lemuria\Exception;

class MkdirException extends FileException
{
	public function __construct(string $directory) {
		parent::__construct('Directory could not be created: ' . $directory);
	}
}
