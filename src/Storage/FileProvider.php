<?php
declare(strict_types = 1);
namespace Lemuria\Storage;

use Lemuria\Exception\DirectoryNotFoundException;
use Lemuria\Exception\FileException;
use Lemuria\Exception\FileNotFoundException;
use Lemuria\Exception\ReadException;
use Lemuria\Exception\WriteException;

class FileProvider implements Provider
{
	private bool $isAvailable = false;

	public function __construct(private string $directory) {
	}

	/**
	 * @throws DirectoryNotFoundException
	 */
	public function exists(string $fileName): bool {
		return file_exists($this->getPath($fileName));
	}

	/**
	 * @throws FileException
	 */
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

	/**
	 * @throws FileException
	 */
	public function write(string $fileName, mixed $content): void {
		$path = $this->setPath($fileName);
		if (file_put_contents($path, $content) !== strlen($content)) {
			throw new WriteException($path);
		}
	}

	/**
	 * @throws DirectoryNotFoundException
	 */
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

	/**
	 * @throws FileException
	 */
	protected function setPath(string $fileName): string {
		if (!$this->isAvailable) {
			$directory = realpath($this->directory);
			if (!$directory) {
				if (!@mkdir($this->directory, 0775, true)) {
					throw new FileException('Create directory failed: ' . $this->directory);
				}
				$this->directory = realpath($this->directory);
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
