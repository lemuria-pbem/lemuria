<?php
declare (strict_types = 1);
namespace Lemuria\Engine\Move;

use Lemuria\Engine\Move;
use Lemuria\Engine\Exception\EngineException;

/**
 * An implementation of a move that hold it's commands in a single file.
 */
class CommandFile implements Move
{
	private string $path;

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
	 * @param string $path
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
	 *
	 * @return string
	 */
	public function __toString(): string {
		return $this->path;
	}

	/**
	 * Get current command.
	 *
	 * @return string
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
				$line .= trim($next);
			}
			if (!$line) {
				continue;
			}
			if (str_ends_with($line, '\\')) {
				$line = substr($line, 0, strlen($line) - 1);
				continue;
			}
			$comment = strpos($line, ';');
			if ($comment !== false) {
				$line = substr($line, 0, $comment);
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
	 *
	 * @return int
	 */
	public function key(): int {
		return $this->index;
	}

	/**
	 * Check if current command is valid.
	 *
	 * @return bool
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
