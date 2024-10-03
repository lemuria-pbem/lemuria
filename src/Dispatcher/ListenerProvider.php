<?php
declare(strict_types = 1);
namespace Lemuria\Dispatcher;

use Psr\EventDispatcher\ListenerProviderInterface;

class ListenerProvider implements ListenerProviderInterface, ListenerRegister
{
	/**
	 * @var array<int, array<int, array<callable>>>
	 */
	protected array $listener = [];

	/**
	 * @var array<string, int>
	 */
	protected array $family = [];

	protected int $nextFamily = 1;

	/**
	 * @var array<string, array<string, int>>
	 */
	protected array $eventMapping = [];

	/**
	 * @var array<int, int>
	 */
	protected array $nextId = [];

	public function addListener(AbstractEvent $event, callable $listener): void {
		if (!isset($this->family[$event->family])) {
			$this->eventMapping[$this->nextFamily] = [];
			$this->nextId[$this->nextFamily]       = 1;
			$this->family[$event->family]          = $this->nextFamily++;
		}
		$f = $this->family[$event->family];

		if (!isset($this->eventMapping[$f][$event::class])) {
			$id = $this->nextId[$f];
			$this->nextId[$f]++;
			$this->eventMapping[$f][$event::class] = $id;
		}
		$id = $this->eventMapping[$f][$event::class];

		$this->listener[$f][$id][] = $listener;
	}

	public function removeListener(AbstractEvent $event, callable $listener): void {
		$f  = $this->family[$event->family] ?? 0;
		$id = $this->eventMapping[$f][$event::class] ?? 0;
		if (isset($this->listener[$f][$id])) {
			foreach ($this->listener[$f][$id] as $i => $eventListener) {
				if ($eventListener === $listener) {
					unset($this->listener[$f][$id][$i]);
				}
			}
		}
	}

	public function getListenersForEvent(object $event): iterable {
		if ($event instanceof AbstractEvent) {
			$f  = $this->family[$event->family] ?? 0;
			$id = $this->eventMapping[$f][$event::class] ?? 0;
			return $this->listener[$f][$id] ?? [];
		}
		return [];
	}

	public function moveListeners(ListenerRegister $from): void {
		$families = array_combine(array_values($from->family), array_keys($from->family));
		$mappings = [];
		foreach ($from->family as $f) {
			$mapping      = $from->eventMapping[$f];
			$mappings[$f] = array_combine(array_values($mapping), array_keys($mapping));
		}

		foreach ($from->listener as $f => $familyListeners) {
			$family  = $families[$f];
			$mapping = $mappings[$f];
			if (!isset($this->family[$family])) {
				$this->eventMapping[$this->nextFamily] = [];
				$this->nextId[$this->nextFamily]       = 1;
				$this->family[$family]                 = $this->nextFamily++;
			}
			$newF = $this->family[$family];

			foreach ($familyListeners as $id => $listeners) {
				$class = $mapping[$id];
				if (!isset($this->eventMapping[$newF][$class])) {
					$newId = $this->nextId[$newF];
					$this->nextId[$newF]++;
					$this->eventMapping[$newF][$class] = $newId;
				}
				$newId = $this->eventMapping[$newF][$class];

				foreach ($listeners as $listener) {
					$this->listener[$newF][$newId][] = $listener;
				}
			}
		}
	}

	/**
	 * @return array<string>
	 */
	public function __sleep(): array {
		return ['family', 'nextFamily', 'eventMapping', 'nextId'];
	}

	public function __wakeup(): void {
		$this->listener = [];
	}
}
