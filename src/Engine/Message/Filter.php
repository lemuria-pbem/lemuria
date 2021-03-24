<?php
declare(strict_types = 1);
namespace Lemuria\Engine\Message;

use Lemuria\Engine\Message;

interface Filter
{
	/**
	 * Checks if the filter retains a Message.
	 */
	public function retains(Message $message): bool;
}
