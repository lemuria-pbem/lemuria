<?php
declare(strict_types = 1);
namespace Lemuria\Storage;

use Lemuria\Exception\FileException;
use Lemuria\Exception\MkdirException;

class RecursiveProvider extends FileProvider
{
	public function glob(string $pattern = '*.txt'): array {
		$dir = dirname($this->getPath($pattern));
		return $this->getFiles($dir, $pattern);
	}

	/**
	 * @throws FileException
	 */
	protected function setPath(string $fileName): string {
		$fileName = trim($fileName, DIRECTORY_SEPARATOR);
		$path     = parent::setPath($fileName);
		if (str_contains($fileName, DIRECTORY_SEPARATOR)) {
			$dirPath = $this->getPath(dirname($fileName));
			if (!is_dir($dirPath)) {
				if (!@mkdir($dirPath, recursive: true)) {
					throw new MkdirException($dirPath);
				}
			}
		}
		return $path;
	}

	private function getFiles(string $dir, string $pattern): array {
		$paths = glob($dir . DIRECTORY_SEPARATOR . $pattern);
		foreach (scandir($dir) as $fileName) {
			$fileName = trim($fileName, '.');
			if ($fileName) {
				$path = $dir . DIRECTORY_SEPARATOR . $fileName;
				if (is_dir($path)) {
					$paths = array_merge($paths, $this->getFiles($path, $pattern));
				}
			}
		}
		return $paths;
	}
}
