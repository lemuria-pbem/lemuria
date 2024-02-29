<?php
declare(strict_types = 1);
namespace Lemuria\Dispatcher;

use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\EventDispatcher\StoppableEventInterface;

readonly class Dispatcher implements EventDispatcherInterface
{
	public function __construct(public ListenerProviderInterface $listenerProvider) {
	}

	public function dispatch(object $event): object {
		if ($event instanceof StoppableEventInterface) {
			foreach ($this->listenerProvider->getListenersForEvent($event) as $listener) {
				if ($event->isPropagationStopped()) {
					break;
				}
				$listener($event);
			}
		} else {
			foreach ($this->listenerProvider->getListenersForEvent($event) as $listener) {
				$listener($event);
			}
		}
		return $event;
	}
}
