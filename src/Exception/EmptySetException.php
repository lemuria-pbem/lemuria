<?php
declare(strict_types = 1);
namespace Lemuria\Exception;

class EmptySetException extends \RuntimeException
{
	public function __construct() {
		parent::__construct('Entity set is empty.');
	}
}
