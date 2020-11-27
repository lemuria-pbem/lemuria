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
	/**
	 * @var resource
	 */
	private $file;

	private int $index;

	private bool $isValid;

	private string $line = '';

	/**
	 * Open specified file path to read commands from.
	 *
	 * @param string $path
	 * @throws EngineException
	 */
	public function __construct(private string $path) {
		$this->file = @fopen($path, 'r');
		if (!$this->file) {
			throw new EngineException('Command file could not be opened.');
		}
		$this->rewind();
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
		while (!feof($this->file)) {
			$line = @fgets($this->file);
			if (is_string($line)) {
				$comment = strpos($line, ';');
				if ($comment !== false) {
					$line = substr($line, 0, $comment);
				}
				$line = trim($line);
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
