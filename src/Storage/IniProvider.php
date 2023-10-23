<?php
declare(strict_types = 1);
namespace Lemuria\Storage;

use Lemuria\Exception\DirectoryNotFoundException;
use Lemuria\Exception\FileException;
use Lemuria\Exception\IniFileException;
use Lemuria\Storage\Ini\Section;
use Lemuria\Storage\Ini\SectionList;

final class IniProvider implements Provider
{
	private RecursiveProvider $provider;

	public function __construct(string $directory) {
		$this->provider = new RecursiveProvider($directory);
	}

	/**
	 * @throws DirectoryNotFoundException
	 */
	public function exists(string $fileName): bool {
		return $this->provider->exists($fileName);
	}

	/**
	 * @throws FileException
	 */
	public function read(string $fileName): SectionList {
		$list    = new SectionList();
		$content = $this->provider->read($fileName);

		$section = null;
		$lines   = null;
		$values  = null;
		$key     = '';
		$value   = '';
		$l       = 0;
		foreach (explode(PHP_EOL, $content) as $line) {
			$l++;
			$line   = trim($line);
			$length = strlen($line);
			if ($length <= 0 || str_starts_with($line, ';')) {
				continue;
			}
			if (str_starts_with($line, '[') && str_ends_with($line, ']')) {
				if ($length <= 2) {
					throw new IniFileException('Missing section name.', $l);
				}
				$name = str_replace('\\=', '=', trim(substr($line, 1, $length - 2)));
				if (!$name) {
					throw new IniFileException('Empty section name.', $l);
				}
				$section = new Section($name);
				$lines   = $section->Lines();
				$values  = $section->Values();
				$list->add($section);
				continue;
			}
			if (!$section) {
				throw new IniFileException('No section defined.', $l);
			}
			if ($this->parseKeyValue($line, $key, $value)) {
				$values[$key] = $value;
			} else {
				$lines->add(str_replace('\\=', '=', $line));
			}
		}
		return $list;
	}

	/**
	 * @param SectionList $content
	 * @throws FileException
	 */
	public function write(string $fileName, mixed $content): void {
		if (!($content instanceof SectionList)) {
			throw new FileException('This provider can write a SectionList only.');
		}

		$list       = '';
		$addNewline = false;
		foreach ($content as $section) {
			if ($addNewline) {
				$list .= PHP_EOL;
			}
			$list .= '[' . $section->Name() . ']' . PHP_EOL;
			foreach ($section->Values() as $key => $value) {
				foreach ($value as $next) {
					$list .= $key . ' = ' . str_replace('=', '\\=', $next) . PHP_EOL;
				}
			}
			foreach ($section->Lines() as $line) {
				$list .= $line . PHP_EOL;
			}
			$addNewline = true;
		}

		$this->provider->write($fileName, $list);
	}

	public function glob(string $pattern = '*'): array {
		return $this->provider->glob($pattern);
	}

	private function parseKeyValue(string $line, string &$key, string &$value): bool {
		$n = strlen($line) - 1;
		$o = 0;
		do {
			$pos = strpos($line, '=', $o);
			if ($pos > 0 && $line[$pos - 1] !== '\\' && $pos < $n) {
				$key   = str_replace('\\=', '=', trim(substr($line, 0, $pos)));
				$value = str_replace('\\=', '=', trim(substr($line, $pos + 1)));
				return true;
			}
			$o = $pos + 1;
		} while ($pos > 0);
		return false;
	}
}
