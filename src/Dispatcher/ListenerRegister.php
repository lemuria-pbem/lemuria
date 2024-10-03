<?php
declare(strict_types = 1);
namespace Lemuria\Dispatcher;

interface ListenerRegister
{
	public function addListener(AbstractEvent $event, callable $listener): void;

	public function removeListener(AbstractEvent $event, callable $listener): void;

	public function moveListeners(self $from): void;
}
