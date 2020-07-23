<?php
declare(strict_types = 1);
namespace Lemuria\Factory;

use function Lemuria\hasPrefix;
use Lemuria\Exception\DirectoryNotFoundException;
use Lemuria\Exception\FileNotFoundException;
use Lemuria\Exception\LemuriaException;

class SingletonGroup
{
	/**
	 * @var string
	 */
	private string $group;

	/**
	 * @var string
	 */
	private string $namespace;

	/**
	 * @var string
	 */
	private string $classDirectory;

	/**
	 * @return string
	 */
	public function getNamespace(): string {
		return $this->namespace;
	}

	/**
	 * @return string
	 */
	public function getGroup(): string {
		return $this->group;
	}

	/**
	 * @param string $group
	 * @param string $namespace
	 * @param string $classDirectory
	 */
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
	 * @param string $group
	 * @throws LemuriaException
	 */
	private function setGroup(string $group): void {
		$this->checkNamespace($group, 'group name');
		$this->group = $group;
	}

	/**
	 * @param string $namespace
	 * @throws LemuriaException
	 */
	private function setNamespace(string $namespace): void {
		$this->checkNamespace($namespace, 'namespace');
		$this->namespace = $namespace;
	}

	/**
	 * @param string $classDirectory
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
	 * @param string $namespace
	 * @param string $type
	 * @throws LemuriaException
	 */
	private function checkNamespace(string $namespace, string $type): void {
		if (preg_match('|^[A-Za-z0-9]+(\\\\[A-Za-z0-9]+)*$|', $namespace) !== 1) {
			throw new LemuriaException('Invalid ' . $type . ', it must be in namespace format.');
		}
	}
}
