<?php
declare(strict_types = 1);
namespace Lemuria;

use Lemuria\Exception\LemuriaException;
use Lemuria\Version\VersionTag;

final class Version implements \ArrayAccess, \Countable, \Iterator
{
	use CountableTrait;
	use IteratorTrait;

	public final const BASE = 'base';

	public final const MODEL = 'model';

	public final const STATISTICS = 'statistics';

	public final const ENGINE = 'engine';

	public final const RENDERERS = 'renderers';

	public final const GAME = 'game';

	private const KEYS = [self::BASE, self::MODEL, self::STATISTICS, self::ENGINE, self::RENDERERS, self::GAME];

	private array $versions;

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

	public function key(): string {
		return self::KEYS[$this->index];
	}

	/**
	 * @return VersionTag[]
	 */
	public function current(): array {
		return $this->versions[self::KEYS[$this->index]];
	}
}
