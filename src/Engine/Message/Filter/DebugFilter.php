<?php
declare(strict_types = 1);
namespace Lemuria\Engine\Message\Filter;

use Lemuria\Engine\Message;
use Lemuria\Engine\Message\Filter;

/**
 * This filter retains all DEBUG messages.
 */
class DebugFilter implements Filter
{
	public function retains(Message $message): bool {
		return $message->Level() === Message::DEBUG;
	}
}
