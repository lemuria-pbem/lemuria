<?php
declare (strict_types = 1);
namespace Lemuria\Engine\Move;

use Lemuria\Engine\Move;
use Lemuria\Engine\Exception\EngineException;

/**
 * An implementation of a move that hold its commands in a single file.
 */
class CommandFile implements \Stringable, Move
{
	protected const REPLACE = ["\t", ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '];

	private readonly string $path;

	/**
	 * @var resource
	 */
	private $file;

	private int $index = 0;

	private bool $isValid = false;

	private string $line = '';

	/**
	 * Open specified file path to read commands from.
	 *
	 * @throws EngineException
	 */
	public function __construct(string $path) {
		$path = realpath($path);
		if (!$path) {
			throw new EngineException('Command file does not exist.');
		}
		$this->path = $path;
		$this->file = @fopen($path, 'r');
		if (!$this->file) {
			throw new EngineException('Command file could not be opened.');
		}
	}

	/**
	 * Close file.
	 */
	public function __destruct() {
		@fclose($this->file);
	}

	/**
	 * Get the path.
	 */
	public function __toString(): string {
		return $this->path;
	}

	/**
	 * Get current command.
	 */
	public function current(): string {
		return $this->line;
	}

	/**
	 * Advance to next command.
	 */
	public function next(): void {
		$line = '';
		while (!feof($this->file)) {
			$next = @fgets($this->file);
			if (is_string($next)) {
				$line .= trim(str_replace(self::REPLACE, ' ', $next));
			}
			if (!$line) {
				continue;
			}
			if (str_ends_with($line, '\\')) {
				$line = trim(substr($line, 0, strlen($line) - 1));
				continue;
			}
			if (strlen($line) > 2 && str_starts_with($line, '//') && $line[2] !== ' ') {
				$line = '//' . ' ' . substr($line, 2);
			}
			$comment = strpos($line, ';');
			if ($comment !== false) {
				$line = trim(substr($line, 0, $comment));
			}
			if ($line) {
				$this->line = $line;
				$this->index++;
				return;
			}
		}
		$this->isValid = false;
	}

	/**
	 * Get current line counter.
	 */
	public function key(): int {
		return $this->index;
	}

	/**
	 * Check if current command is valid.
	 */
	public function valid(): bool {
		return $this->isValid;
	}

	/**
	 * Reset to first command.
	 */
	public function rewind(): void {
		if (!rewind($this->file)) {
			throw new EngineException('Could not rewind command file.');
		}
		$this->index   = -1;
		$this->isValid = true;
		$this->next();
	}
}
