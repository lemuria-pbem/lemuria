<?php
declare(strict_types = 1);
namespace Lemuria\Engine\Message\Filter;

use Lemuria\Engine\Message;
use Lemuria\Engine\Message\Filter;

/**
 * This filter never retains any message.
 */
class NullFilter implements Filter
{
	public function retains(Message $message): bool {
		return false;
	}
}
