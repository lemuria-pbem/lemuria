<?php
declare(strict_types = 1);
namespace Lemuria\Factory;

use JetBrains\PhpStorm\Pure;

use function Lemuria\hasPrefix;
use Lemuria\Exception\DirectoryNotFoundException;
use Lemuria\Exception\FileNotFoundException;
use Lemuria\Exception\LemuriaException;

class SingletonGroup
{
	private readonly string $group;

	private readonly string $namespace;

	private readonly string $classDirectory;

	#[Pure] public function getNamespace(): string {
		return $this->namespace;
	}

	#[Pure] public function getGroup(): string {
		return $this->group;
	}

	public function __construct(string $group, string $namespace, string $classDirectory) {
		$this->setGroup($group);
		$this->setNamespace($namespace);
		$this->setClassDirectory($classDirectory);
	}

	/**
	 * @return string[]
	 */
	public function getSingletons(): array {
		$singletons     = [];
		$groupDirectory = str_replace('\\', DIRECTORY_SEPARATOR, $this->group);
		$classDirectory = $this->classDirectory . DIRECTORY_SEPARATOR . $groupDirectory;
		foreach (glob($classDirectory . DIRECTORY_SEPARATOR . '*.php') as $path) {
			if (!is_file($path)) {
				throw new FileNotFoundException($path);
			}
			$fileName = basename($path);
			if (hasPrefix('Abstract', $fileName)) {
				continue;
			}
			$singletons[] = substr($fileName, 0, strlen($fileName) - 4);
		}
		return $singletons;
	}

	/**
	 * @throws LemuriaException
	 */
	private function setGroup(string $group): void {
		$this->checkNamespace($group, 'group name');
		$this->group = $group;
	}

	/**
	 * @throws LemuriaException
	 */
	private function setNamespace(string $namespace): void {
		$this->checkNamespace($namespace, 'namespace');
		$this->namespace = $namespace;
	}

	/**
	 * @throws LemuriaException
	 */
	private function setClassDirectory(string $classDirectory): void {
		$directory = realpath($classDirectory);
		if (!$directory || !is_dir($directory)) {
			throw new DirectoryNotFoundException($classDirectory);
		}
		$this->classDirectory = $directory;
	}

	/**
	 * @throws LemuriaException
	 */
	private function checkNamespace(string $namespace, string $type): void {
		if (preg_match('|^[A-Za-z0-9]+(\\\\[A-Za-z0-9]+)*$|', $namespace) !== 1) {
			throw new LemuriaException('Invalid ' . $type . ', it must be in namespace format.');
		}
	}
}
