<?php
declare(strict_types = 1);
namespace Lemuria\Version;

use Lemuria\Exception\FileNotFoundException;
use Lemuria\Exception\LemuriaException;
use Lemuria\Exception\ReadException;

class VersionFinder
{
	protected string $composer;

	/**
	 * @throws FileNotFoundException
	 */
	public function __construct(string $baseDirectory) {
		$path     = $baseDirectory . DIRECTORY_SEPARATOR . 'composer.json';
		$composer = realpath($path);
		if (!$composer) {
			throw new FileNotFoundException($path);
		}
		$this->composer = $composer;
	}

	/**
	 * @throws ReadException
	 */
	public function get(): VersionTag {
		$json = file_get_contents($this->composer);
		if (!$json) {
			throw new ReadException($this->composer);
		}
		$json = json_decode($json, true);
		if (!$json) {
			throw new LemuriaException('Invalid composer.json file: ' . $this->composer);
		}
		$name    = $json['name'] ?? null;
		$version = $json['version'] ?? null;
		if (!is_string($name) || !is_string($version)) {
			throw new LemuriaException('Invalid name or version in composer.json file: ' . $this->composer);
		}
		$names = explode('/', $name);
		return new VersionTag(array_pop($names), $version);
	}
}
