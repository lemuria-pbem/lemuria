<?php
declare(strict_types = 1);
namespace Lemuria\Storage\Ini;

use Lemuria\IteratorTrait;

class SectionList implements \Countable, \Iterator
{
	use IteratorTrait;

	/**
	 * @var array<Section>
	 */
	private array $list = [];

	public function current(): Section {
		return $this->list[$this->key()];
	}

	/**
	 * @return array<Section>
	 */
	public function getSections(): array {
		return $this->list;
	}

	public function add(Section $section): static {
		$this->list[] = $section;
		$this->count++;
		return $this;
	}

	public function clear(): static {
		$this->list  = [];
		$this->index = 0;
		$this->count = 0;
		return $this;
	}
}
