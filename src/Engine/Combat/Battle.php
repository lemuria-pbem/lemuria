<?php
declare(strict_types = 1);
namespace Lemuria\Engine\Combat;

use Lemuria\Identifiable;
use Lemuria\Model\Location;
use Lemuria\Serializable;

interface Battle extends \Iterator, Serializable
{
	public function Location(): Location;

	/**
	 * @return Identifiable[]
	 */
	public function Participants(): array;
}
