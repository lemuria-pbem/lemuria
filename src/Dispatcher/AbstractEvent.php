<?php
declare(strict_types = 1);
namespace Lemuria\Dispatcher;

use Psr\EventDispatcher\StoppableEventInterface;

abstract readonly class AbstractEvent implements StoppableEventInterface
{
	public string $family;

	public function isPropagationStopped(): bool {
		return false;
	}
}
