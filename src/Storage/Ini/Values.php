<?php
declare(strict_types = 1);
namespace Lemuria\Storage\Ini;

use Lemuria\Exception\LemuriaException;

class Values implements \ArrayAccess, \Countable, \Iterator
{
	/**
	 * @var array<string, Value>
	 */
	private array $values = [];

	private int $index = 0;

	/**
	 * @var array<string>
	 */
	private array $keys;

	/**
	 * @param string $offset
	 * @return bool
	 */
	public function offsetExists(mixed $offset): bool {
		if (!is_string($offset)) {
			throw new LemuriaException('Only string offsets are allowed.');
		}
		return array_key_exists($offset, $this->values);
	}

	/**
	 * @param string $offset
	 */
	public function offsetGet(mixed $offset): ?Value {
		if (!is_string($offset)) {
			throw new LemuriaException('Only string offsets are allowed.');
		}
		return $this->values[$offset] ?? null;
	}

	/**
	 * @param string $offset
	 * @param string $value
	 */
	public function offsetSet(mixed $offset, mixed $value): void {
		if (!is_string($offset)) {
			throw new LemuriaException('Only string offsets are allowed.');
		}
		$key = trim($offset);
		if (!$key) {
			throw new LemuriaException('Invalid empty offset used as key.');
		}
		if ($value instanceof Value) {
			$this->values[$key] = $value;
		} else {
			if (!is_string($value)) {
				throw new LemuriaException('Only Value objects or strings are allowed as value.');
			}
			if (isset($this->values[$key])) {
				$this->values[$key]->add($value);
			} else {
				$this->values[$key] = new Value(trim($value));
			}
		}
	}

	/**
	 * @param string $offset
	 */
	public function offsetUnset(mixed $offset): void {
		if (!is_string($offset)) {
			throw new LemuriaException('Only string offsets are allowed.');
		}
		unset($this->values[$offset]);
	}

	public function count(): int {
		return count($this->values);
	}


	public function current(): Value {
		return $this->values[$this->key()];
	}

	public function key(): string {
		return $this->keys[$this->index];
	}

	public function next(): void {
		$this->index++;
	}

	public function rewind(): void {
		$this->index = 0;
		$this->keys  = array_keys($this->values);
	}

	public function valid(): bool {
		return $this->index < count($this->values);
	}

	public function isEmpty(): bool {
		return empty($this->values);
	}
}
