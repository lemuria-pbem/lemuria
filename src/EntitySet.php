<?php
declare (strict_types = 1);
namespace Lemuria;

use JetBrains\PhpStorm\ExpectedValues;
use JetBrains\PhpStorm\Pure;

use Lemuria\Exception\EmptySetException;
use Lemuria\Exception\EntitySetException;
use Lemuria\Exception\EntitySetReplaceException;
use Lemuria\Exception\LemuriaException;
use Lemuria\Exception\UnserializeEntitySetException;

/**
 * A simple set of entities.
 */
abstract class EntitySet implements \ArrayAccess, \Countable, \Iterator, Serializable
{
	/**
	 * @var int[]
	 */
	private array $indices = [];

	/**
	 * @var array(int=>Id)
	 */
	private array $entities = [];

	private int $index = 0;

	private int $count = 0;

	/**
	 * Init the set for a Collector.
	 */
	#[Pure] public function __construct(private ?Collector $collector = null) {
	}

	/**
	 * Check if an offset is in the set.
	 *
	 * @param int $offset
	 */
	#[Pure] public function offsetExists(mixed $offset): bool {
		return $offset >= 0 && $offset < $this->count;
	}

	/**
	 * Get an entity from the set.
	 *
	 * @param int $offset
	 * @throws \OutOfBoundsException
	 */
	public function offsetGet(mixed $offset): Entity {
		if ($this->offsetExists($offset)) {
			return $this->get($this->entities[$this->indices[$offset]]);
		}
		throw new \OutOfBoundsException();
	}

	/**
	 * Not implemented.
	 *
	 * @param int $offset
	 * @param Entity $value
	 * @throws LemuriaException
	 */
	public function offsetSet(mixed $offset, mixed $value): void {
		throw new LemuriaException('Setting via ArrayAccess is intentionally not implemented.');
	}

	/**
	 * Remove an entity from the set.
	 *
	 * @param int $offset
	 * @throws \OutOfBoundsException
	 */
	public function offsetUnset(mixed $offset): void {
		if ($this->offsetExists($offset)) {
			$this->removeEntity($this->entities[$this->indices[$offset]]);
		} else {
			throw new \OutOfBoundsException();
		}
	}

	/**
	 * Get the number of items in the set.
	 *
	 * @return int
	 */
	#[Pure] public function count(): int {
		return $this->count;
	}

	public function current(): ?Entity {
		$key = $this->key();
		return $key ? $this->get($this->entities[$key]) : null;
	}

	#[Pure] public function key(): ?int {
		return $this->indices[$this->index] ?? null;
	}

	public function next(): void {
		$this->index++;
	}

	public function rewind(): void {
		$this->index = 0;
	}

	#[Pure] public function valid(): bool {
		return $this->index < $this->count;
	}

	/**
	 * Get a plain data array of the model's data.
	 *
	 * @return int[]
	 */
	#[Pure] public function serialize(): array {
		$data = [];
		foreach ($this->entities as $id /** @var Id $id */) {
			$data[] = $id->Id();
		}
		return $data;
	}

	/**
	 * Restore the model's data from serialized data.
	 *
	 * @param int[] $data
	 */
	public function unserialize(array $data): Serializable {
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
	 * Check if an entity belongs to the set.
	 */
	#[Pure] public function has(Id $id): bool {
		return isset($this->entities[$id->Id()]);
	}

	/**
	 * Clear the set.
	 */
	public function clear(): EntitySet {
		$this->indices  = [];
		$this->entities = [];
		$this->index    = 0;
		$this->count    = 0;
		return $this;
	}

	/**
	 * Set the Collector in all entities.
	 */
	public function addCollectorsToAll(): EntitySet {
		if ($this->hasCollector()) {
			foreach ($this->entities as $id/* @var Id $id */) {
				/* @var Collectible $collectible */
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
	public function random(): Entity {
		if ($this->count > 0) {
			$index = $this->indices[rand(max: $this->count - 1)];
			return $this->entities[$index];
		}
		throw new EmptySetException();
	}

	/**
	 * Get an Entity by ID.
	 */
	abstract protected function get(Id $id): Entity;

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
	 * Add an entity's ID to the set.
	 */
	protected function addEntity(Id $id): void {
		if (!isset($this->entities[$id->Id()])) {
			$this->entities[$id->Id()]     = $id;
			$this->indices[$this->count++] = $id->Id();
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
	protected function reorderEntity(Id $entity, Id $position,
									 #[ExpectedValues(valuesFromClass: Reorder::class)] int $reorder = Reorder::FLIP): void
	{
		$e = $entity->Id();
		if (!isset($this->entities[$e])) {
			throw new EntitySetException($entity);
		}
		$p = $position->Id();
		if (!isset($this->entities[$p])) {
			throw new EntitySetException($position);
		}

		$newIndices  = [];
		$newEntities = [];
		foreach ($this->entities as $i => $id) {
			if ($i === $e) {
				if ($reorder === Reorder::FLIP) {
					$newEntities[$p] = $position;
					$newIndices[]    = $p;
				}
			} elseif ($i === $p) {
				if ($reorder <= Reorder::BEFORE) {
					$newEntities[$e] = $entity;
					$newIndices[]    = $e;
					$newEntities[$p] = $position;
					$newIndices[]    = $p;
				} elseif ($reorder >= Reorder::AFTER) {
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
		$this->indices = $order->sort($this);
		$this->rewind();
	}
}
