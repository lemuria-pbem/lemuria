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
	private const string IDENTIFIER_HASH = '%IDENTIFIER_HASH%';

	private const string CACHE_FILE_TEMPLATE = 'FC_' . self::IDENTIFIER_HASH . '.bin';

	private string $directory;

	private string $fileIdentifier = __CLASS__;

	private array $cache;

	public static function delete(string $storageDirectory, ?string $fileIdentifier = null): bool {
		if (!$fileIdentifier) {
			$fileIdentifier = __CLASS__;
		}
		$path = self::path($storageDirectory, $fileIdentifier);
		return is_file($path) && @unlink($path);
	}

	public function __construct(?Lemuria $lemuria = null) {
		if ($lemuria) {
			$this->cache = [$lemuria::class => $lemuria];
		}
	}

	public function FileIdentifier(): string {
		return $this->fileIdentifier;
	}

	public function get(string $class): ?object {
		return $this->cache[$class] ?? null;
	}

	public function set(object $object): self {
		$this->cache[$object::class] = $object;
		return $this;
	}

	public function setFileIdentifier(string $identifier): self {
		$this->fileIdentifier = strlen($identifier) > 0 ? $identifier : __CLASS__;
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
		$path = self::path($this->directory, $this->fileIdentifier);
		if (!file_put_contents($path, $content)) {
			throw new FileException('Could not write cache to ' . $path . '.');
		}
		return $this;
	}

	/**
	 * @throws FileException
	 */
	public function restore(): Lemuria {
		$path = $this->path($this->directory, $this->fileIdentifier);
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

	private static function path(string $directory, string $fileIdentifier): string {
		$hash     = md5($fileIdentifier);
		$fileName = str_replace(self::IDENTIFIER_HASH, $hash, self::CACHE_FILE_TEMPLATE);
		return $directory . DIRECTORY_SEPARATOR . $fileName;
	}
}
