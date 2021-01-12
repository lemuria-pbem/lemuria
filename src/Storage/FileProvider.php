<?php
declare(strict_types = 1);
namespace Lemuria\Storage;

use JetBrains\PhpStorm\Pure;

use Lemuria\Exception\DirectoryNotFoundException;
use Lemuria\Exception\FileException;
use Lemuria\Exception\FileNotFoundException;
use Lemuria\Exception\ReadException;
use Lemuria\Exception\WriteException;

class FileProvider
{
	public const DEFAULT = '';

	private bool $isAvailable = false;

	#[Pure]
	public function __construct(private string $directory) {
	}

	public function exists(string $fileName): bool {
		return file_exists($this->getPath($fileName));
	}

	public function read(string $fileName): string {
		$path = $this->getPath($fileName);
		if (!is_file($path)) {
			throw new FileNotFoundException($path);
		}
		$content = @file_get_contents($path);
		if ($content === false) {
			throw new ReadException($path);
		}
		return $content;
	}

	public function write(string $fileName, string $content): void {
		$path = $this->setPath($fileName);
		if (file_put_contents($path, $content) !== strlen($content)) {
			throw new WriteException($path);
		}
	}

	protected function getPath(string $fileName): string {
		if (!$this->isAvailable) {
			$directory = realpath($this->directory);
			if (!$directory) {
				throw new DirectoryNotFoundException($this->directory);
			}
			$this->directory   = $directory;
			$this->isAvailable = true;
			if (!is_dir($this->directory)) {
				throw new DirectoryNotFoundException($this->directory);
			}
		}
		return $this->directory . DIRECTORY_SEPARATOR . $fileName;
	}

	protected function setPath(string $fileName): string {
		if (!$this->isAvailable) {
			$directory = realpath($this->directory);
			if (!$directory) {
				if (!@mkdir($this->directory, 0775, true)) {
					throw new FileException('Create directory failed: ' . $this->directory);
				}
				$this->directory = realpath($directory);
				if (!$this->directory) {
					throw new DirectoryNotFoundException($directory);
				}
			} else {
				$this->directory = $directory;
			}
			$this->isAvailable = true;
			if (!is_dir($this->directory)) {
				throw new DirectoryNotFoundException($this->directory);
			}
		}
		return $this->directory . DIRECTORY_SEPARATOR . $fileName;
	}
}
