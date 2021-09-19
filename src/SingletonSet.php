<?php
declare(strict_types = 1);
namespace Lemuria;

use JetBrains\PhpStorm\Pure;

use Lemuria\Exception\SingletonSetException;
use Lemuria\Exception\SingletonException;

class SingletonSet implements \ArrayAccess, \Countable, \Iterator, Serializable
{
	use CountableTrait;
	use IteratorTrait;

	/**
	 * @var string[]
	 */
	private array $indices = [];

	/**
	 * @var array(string=>Singleton)
	 */
	private array $singletons = [];

	/**
	 * Check if a singleton is in the set.
	 *
	 * @param Singleton|string $offset
	 */
	#[Pure] public function offsetExists(mixed $offset): bool {
		$class = getClass($offset);
		return isset($this->singletons[$class]);
	}

	/**
	 * Get a singleton from the set.
	 *
	 * @param Singleton|string $offset
	 */
	public function offsetGet(mixed $offset): Item {
		$class = getClass($offset);
		if (isset($this->singletons[$class])) {
			return $this->singletons[$class];
		}
		throw new SingletonSetException($offset);
	}

	/**
	 * Add a singleton to the set.
	 *
	 * @param mixed $offset
	 * @param Singleton $value
	 */
	public function offsetSet(mixed $offset, mixed $value): void {
		$this->add($value);
	}

	/**
	 * Delete a singleton from the set.
	 *
	 * @param Singleton|string $offset
	 */
	public function offsetUnset(mixed $offset): void {
		$this->delete($offset);
	}

	#[Pure] public function current(): ?Singleton {
		$key = $this->key();
		return $key !== null ? $this->singletons[$key] : null;
	}

	#[Pure] public function key(): ?string {
		return $this->indices[$this->index] ?? null;
	}

	/**
	 * Get a plain data array of the model's data.
	 *
	 * @return string[]
	 */
	#[Pure] public function serialize(): array {
		return array_keys($this->singletons);
	}

	/**
	 * Restore the model's data from serialized data.
	 *
	 * @param string[] $data
	 */
	public function unserialize(array $data): Serializable {
		$this->clear();
		foreach ($data as $class) {
			$singleton = Lemuria::Builder()->create($class);
			$this->add($singleton);
		}
		return $this;
	}

	/**
	 * Add a singleton to the set.
	 */
	public function add(string|Singleton $singleton): void {
		$class = getClass($singleton);
		if (is_string($singleton)) {
			$singleton = Lemuria::Builder()->create($class);
		}
		$this->validateSingleton($singleton);
		if (!isset($this->singletons[$class])) {
			$this->singletons[$class] = $singleton;
			$this->indices[]          = $class;
			$this->count++;
		}
	}

	/**
	 * Remove the singleton of the specified class from the set.
	 */
	public function delete(string|Singleton $singleton): void {
		$class = getClass($singleton);
		unset($this->singletons[$class]);
		$this->indices = array_keys($this->singletons);
		$this->count--;
		if ($this->index >= $this->count) {
			if ($this->count === 0) {
				$this->index = 0;
			} else {
				$this->index--;
			}
		}
	}

	/**
	 * Clear the set.
	 */
	public function clear(): SingletonSet {
		$this->indices    = [];
		$this->singletons = [];
		$this->index      = 0;
		$this->count      = 0;
		return $this;
	}

	/**
	 * Fill the set with a copy of the singletons of given set.
	 */
	public function fill(SingletonSet $set): SingletonSet {
		foreach ($set->singletons as $singleton) {
			$this->validateSingleton($singleton);
			$this->add($singleton);
		}
		return $this;
	}

	protected function validateSingleton(mixed $singleton): void {
		if (!($singleton instanceof Singleton)) {
			throw new SingletonException($singleton);
		}
	}
}
