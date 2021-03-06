<?php
declare(strict_types = 1);
namespace Lemuria;

use Lemuria\Exception\LemuriaException;
use Lemuria\Version\VersionTag;

final class Version implements \ArrayAccess, \Countable, \Iterator
{
	public const BASE = 'base';

	public const MODEL = 'model';

	public const ENGINE = 'engine';

	public const RENDERERS = 'renderers';

	public const GAME = 'game';

	private const KEYS = [self::BASE, self::MODEL, self::ENGINE, self::RENDERERS, self::GAME];

	private array $versions;

	private int $index = 0;

	private int $count;

	public function __construct() {
		$this->versions = array_fill_keys(self::KEYS, []);
		$this->count    = count(self::KEYS);
	}

	/**
	 * @param string $offset
	 */
	public function offsetExists(mixed $offset): bool {
		return isset($this->versions[$offset]) && count($this->versions[$offset]) > 0;
	}

	/**
	 * @param string $offset
	 * @return VersionTag[]
	 */
	public function offsetGet(mixed $offset): array {
		if (!isset($this->versions[$offset])) {
			throw new LemuriaException();
		}
		return $this->versions[$offset];
	}

	/**
	 * @param string $offset
	 * @param VersionTag $value
	 */
	public function offsetSet(mixed $offset, mixed $value): void {
		if (!isset($this->versions[$offset])) {
			throw new LemuriaException();
		}
		$this->versions[$offset][] = $value;
	}

	/**
	 * @param string $offset
	 */
	public function offsetUnset(mixed $offset): void {
		if (!isset($this->versions[$offset])) {
			throw new LemuriaException();
		}
		$this->versions[$offset] = [];
	}

	public function count(): int {
		return $this->count;
	}

	public function key(): string {
		return self::KEYS[$this->index];
	}

	/**
	 * @return VersionTag[]
	 */
	public function current(): array {
		return $this->versions[self::KEYS[$this->index]];
	}

	public function next(): void {
		$this->index++;
	}

	public function valid(): bool {
		return $this->index < $this->count;
	}

	public function rewind(): void {
		$this->index = 0;
	}
}
