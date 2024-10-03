<?php
declare(strict_types = 1);
namespace Lemuria\Cache;

use Lemuria\Exception\DirectoryNotFoundException;
use Lemuria\Exception\FileException;
use Lemuria\Exception\FileNotFoundException;
use Lemuria\Exception\LemuriaException;
use Lemuria\Lemuria;

final class FastCache
{
	private string $directory;

	private array $cache;

	public function __construct(?Lemuria $lemuria = null) {
		if ($lemuria) {
			$this->cache = [$lemuria::class => $lemuria];
		}
	}

	public function get(string $class): ?object {
		return $this->cache[$class] ?? null;
	}

	public function set(object $object): self {
		$this->cache[$object::class] = $object;
		return $this;
	}

	/**
	 * @throws DirectoryNotFoundException
	 */
	public function setStorage(string $directory): self {
		$directory = realpath($directory);
		if (!$directory || !is_dir($directory)) {
			throw new DirectoryNotFoundException($directory);
		}
		$this->directory = $directory;
		return $this;
	}

	/**
	 * @throws FileException
	 */
	public function persist(): self {
		$content = igbinary_serialize($this->cache);
		if (!$content) {
			throw new LemuriaException('Cache serialization error.');
		}
		if (!file_put_contents($this->path(), $content)) {
			throw new FileException('Could not write cache to ' . $this->path() . '.');
		}
		return $this;
	}

	/**
	 * @throws FileException
	 */
	public function restore(): Lemuria {
		$path = $this->path();
		if (is_file($path)) {
			$content = file_get_contents($path);
			if ($content) {
				$this->cache = igbinary_unserialize($content);
				if (isset($this->cache[Lemuria::class])) {
					return $this->cache[Lemuria::class];
				}
				throw new FileException('Invalid FastCache file.');
			}
			throw new FileException('FastCache file could not be read.');
		}
		throw new FileNotFoundException($path);
	}

	private function path(): string {
		return $this->directory . DIRECTORY_SEPARATOR .  md5(__CLASS__);
	}
}
