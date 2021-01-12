<?php
declare(strict_types = 1);
namespace Lemuria\Storage;

use Lemuria\Exception\DirectoryNotFoundException;
use Lemuria\Exception\FileException;

interface Provider
{
	public const DEFAULT = '';

	public function __construct(string $directory);

	/**
	 * @throws DirectoryNotFoundException
	 */
	public function exists(string $fileName): bool;

	/**
	 * @throws FileException
	 */
	public function read(string $fileName): mixed;

	/**
	 * @throws FileException
	 */
	public function write(string $fileName, mixed $content): void;
}
