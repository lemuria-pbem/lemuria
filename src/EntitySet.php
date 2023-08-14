<?php
declare (strict_types = 1);
namespace Lemuria;

use Lemuria\Exception\EmptySetException;
use Lemuria\Exception\EntitySetException;
use Lemuria\Exception\EntitySetReplaceException;
use Lemuria\Exception\LemuriaException;
use Lemuria\Exception\UnserializeEntitySetException;

/**
 * A simple set of entities.
 */
abstract class EntitySet implements \ArrayAccess, \Countable, \Iterator, EntityContainer, Serializable
{
	use CountableTrait;
	use IteratorTrait;

	/**
	 * @var array<int>
	 */
	private array $indices = [];

	/**
	 * @var array<int, Id>
	 */
	private array $entities = [];

	/**
	 * Init the set for a Collector.
	 */
	public function __construct(private readonly ?Collector $collector = null) {
	}

	/**
	 * Check if an entity is in the set.
	 *
	 * @param int|Id $offset
	 */
	public function offsetExists(mixed $offset): bool {
		$id = $offset instanceof Id ? $offset->Id() : $offset;
		return isset($this->entities[$id]);
	}

	/**
	 * Get an entity from the set.
	 *
	 * @param int|Id $offset
	 * @throws EntitySetException
	 */
	public function offsetGet(mixed $offset): Identifiable {
		$id = $offset instanceof Id ? $offset : new Id($offset);
		return $this->get($id);
	}

	/**
	 * Not implemented.
	 *
	 * @param int|Id $offset
	 * @param Identifiable $value
	 * @throws LemuriaException
	 */
	public function offsetSet(mixed $offset, mixed $value): never {
		throw new LemuriaException('Setting via ArrayAccess is intentionally not implemented.');
	}

	/**
	 * Remove an entity from the set.
	 *
	 * @param int|Id $offset
	 * @throws EntitySetException
	 */
	public function offsetUnset(mixed $offset): void {
		$id = $offset instanceof Id ? $offset : new Id($offset);
		$this->removeEntity($id);
	}

	public function current(): ?Identifiable {
		$key = $this->key();
		return $key ? $this->get($this->entities[$key]) : null;
	}

	public function key(): ?int {
		return $this->indices[$this->index] ?? null;
	}

	/**
	 * Check if an entity belongs to the set.
	 */
	public function contains(Identifiable $identifiable): bool {
		return $this->has($identifiable->Id());
	}

	/**
	 * Check if an ID belongs to the set.
	 */
	public function has(Id $id): bool {
		return isset($this->entities[$id->Id()]);
	}

	/**
	 * Get a plain data array of the model's data.
	 *
	 * @return array<int>
	 */
	public function serialize(): array {
		$data = [];
		foreach ($this->entities as $id) {
			$data[] = $id->Id();
		}
		return $data;
	}

	/**
	 * Restore the model's data from serialized data.
	 *
	 * @param array<int> $data
	 */
	public function unserialize(array $data): static {
		if ($this->count > 0) {
			$this->clear();
		}

		foreach ($data as $id) {
			if (!is_int($id)) {
				throw new UnserializeEntitySetException();
			}
			$this->addEntity(new Id($id));
		}
		return $this;
	}

	/**
	 * Clear the set.
	 */
	public function clear(): static {
		$this->indices  = [];
		$this->entities = [];
		$this->index    = 0;
		$this->count    = 0;
		return $this;
	}

	public function fill(EntitySet $set): static {
		$this->indices  = $set->indices;
		$this->entities = $set->entities;
		$this->index    = 0;
		$this->count    = $set->count;
		return $this;
	}

	/**
	 * Set the Collector in all entities.
	 */
	public function addCollectorsToAll(): static {
		if ($this->hasCollector()) {
			foreach ($this->entities as $id) {
				/** @var Collectible $collectible */
				$collectible = $this->get($id);
				$collectible->addCollector($this->collector());
			}
		}
		return $this;
	}

	/**
	 * Replace an entity in the set with another one that is not part of the set.
	 *
	 * @throws EntitySetException The entity is not part of the set.
	 * @throws EntitySetReplaceException The replacement is part of the set.
	 */
	public function replace(Id $search, Id $replace): void {
		$s = $search->Id();
		if (!isset($this->entities[$s])) {
			throw new EntitySetException($search);
		}
		$r = $replace->Id();
		if (isset($this->entities[$r])) {
			throw new EntitySetReplaceException($replace);
		}

		$i           = 0;
		$newEntities = [];
		foreach ($this->entities as $e => $id) {
			if ($e === $s) {
				$newEntities[$r]   = $replace;
				$this->indices[$i] = $r;
			} else {
				$newEntities[$e] = $id;
			}
			$i++;
		}
		$this->entities = $newEntities;
	}

	/**
	 * Get a randomly selected Entity.
	 *
	 * @throws EmptySetException
	 */
	public function random(): Identifiable {
		if ($this->count > 0) {
			$index = $this->indices[randInt(max: $this->count - 1)];
			return $this->get($this->entities[$index]);
		}
		throw new EmptySetException();
	}

	/**
	 * Get an Entity by ID.
	 */
	abstract protected function get(Id $id): Identifiable;

	/**
	 * Check if a Collector is set.
	 */
	protected function hasCollector(): bool {
		return $this->collector instanceof Collector;
	}

	/**
	 * Get the Collector.
	 */
	protected function collector(): Collector {
		return $this->collector;
	}

	/**
	 * Get the first Entity.
	 */
	protected function first(): ?Id {
		if ($this->count > 0) {
			return $this->entities[$this->indices[0]];
		}
		return null;
	}

	/**
	 * Get the last Entity.
	 */
	protected function last(): ?Id {
		if ($this->count > 0) {
			return $this->entities[$this->indices[$this->count - 1]];
		}
		return null;
	}

	/**
	 * Add an entity's ID to the set.
	 */
	protected function addEntity(Id $id): void {
		$i = $id->Id();
		if (!isset($this->entities[$i])) {
			$this->entities[$i]            = $id;
			$this->indices[$this->count++] = $i;
		}
	}

	/**
	 * Remove an entity's ID from the set.
	 *
	 * @throws EntitySetException The entity is not part of the set.
	 */
	protected function removeEntity(Id $id): void {
		if (!isset($this->entities[$id->Id()])) {
			throw new EntitySetException($id);
		}

		unset($this->entities[$id->Id()]);
		$this->indices = array_keys($this->entities);
		$this->count--;
		if ($this->index >= $this->count) {
			if ($this->count <= 0) {
				$this->index = 0;
			} else {
				$this->index--;
			}
		}
	}

	/**
	 * Change position of two entities given by their ID.
	 *
	 * @throws EntitySetException One of the entities is not part of the set.
	 */
	protected function reorderEntity(Id $entity, Id $position, Reorder $reorder = Reorder::Flip): void
	{
		$e = $entity->Id();
		if (!isset($this->entities[$e])) {
			throw new EntitySetException($entity);
		}
		$p = $position->Id();
		if ($p === $e) {
			return;
		}
		if (!isset($this->entities[$p])) {
			throw new EntitySetException($position);
		}

		$newIndices  = [];
		$newEntities = [];
		foreach ($this->entities as $i => $id) {
			if ($i === $e) {
				if ($reorder === Reorder::Flip) {
					$newEntities[$p] = $position;
					$newIndices[]    = $p;
				}
			} elseif ($i === $p) {
				if ($reorder <= Reorder::Before) {
					$newEntities[$e] = $entity;
					$newIndices[]    = $e;
					$newEntities[$p] = $position;
					$newIndices[]    = $p;
				} elseif ($reorder >= Reorder::After) {
					$newEntities[$p] = $position;
					$newIndices[]    = $p;
					$newEntities[$e] = $entity;
					$newIndices[]    = $e;
				} else {
					$newEntities[$e] = $entity;
					$newIndices[]    = $e;
				}
			} else {
				$newEntities[$i] = $id;
				$newIndices[]    = $i;
			}
		}
		$this->entities = $newEntities;
		$this->indices  = $newIndices;
		$this->index    = 0;
	}

	/**
	 * Sort the set using specified order.
	 */
	protected function sortUsing(EntityOrder $order): void {
		$this->indices  = $order->sort($this);
		$this->entities = [];
		foreach ($this->indices as $id) {
			$this->entities[$id] = new Id($id);
		}
		$this->rewind();
	}
}
