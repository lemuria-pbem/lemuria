<?php
declare(strict_types = 1);
namespace Lemuria;

use Lemuria\Exception\LemuriaException;
use Lemuria\Version\Module;
use Lemuria\Version\VersionTag;

/**
 * @\ArrayAccess <Module, VersionTag>
 * @\Iterator <Module, VersionTag[]>
 */
final class Version implements \ArrayAccess, \Countable, \Iterator
{
	use CountableTrait;
	use IteratorTrait;

	private array $modules = [];

	private array $versions = [];

	public function __construct() {
		foreach (Module::cases() as $module) {
			$this->modules[]                = $module->value;
			$this->versions[$module->value] = [];
		}
		$this->count = count($this->versions);
	}

	/**
	 * @param Module $offset
	 */
	public function offsetExists(mixed $offset): bool {
		return isset($this->versions[$offset->value]) && count($this->versions[$offset->value]) > 0;
	}

	/**
	 * @param Module $offset
	 * @return VersionTag[]
	 */
	public function offsetGet(mixed $offset): array {
		if (!isset($this->versions[$offset->value])) {
			throw new LemuriaException();
		}
		return $this->versions[$offset->value];
	}

	/**
	 * @param Module $offset
	 * @param VersionTag $value
	 */
	public function offsetSet(mixed $offset, mixed $value): void {
		if (!isset($this->versions[$offset->value])) {
			throw new LemuriaException();
		}
		$this->versions[$offset->value][] = $value;
	}

	/**
	 * @param Module $offset
	 */
	public function offsetUnset(mixed $offset): void {
		if (!isset($this->versions[$offset->value])) {
			throw new LemuriaException();
		}
		$this->versions[$offset->value] = [];
	}

	public function key(): Module {
		return $this->modules[$this->index];
	}

	/**
	 * @return VersionTag[]
	 */
	public function current(): array {
		return $this->versions[$this->modules[$this->index]];
	}
}
