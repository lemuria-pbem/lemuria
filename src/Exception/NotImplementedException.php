<?php
declare(strict_types = 1);
namespace Lemuria\Exception;

class NotImplementedException extends LemuriaException
{
	public function __construct() {
		parent::__construct('Intentionally not implemented.');
	}
}
