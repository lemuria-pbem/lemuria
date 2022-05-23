<?php
declare (strict_types = 1);
namespace Lemuria\Factory;

use Lemuria\Exception\SingletonException;

/**
 * A map of Lemuria Singleton classes used for instantiation.
 */
class SingletonMap
{
	/**
	 * @var array<int, string>
	 */
	private array $groups = [];

	/**
	 * @var array<int, int>
	 */
	private array $namespaceIndex = [];

	/**
	 * @var array<int, string>
	 */
	private array $namespaces = [];

	/**
	 * @var array<string, int>
	 */
	private array $map = [];

	/**
	 * Get the full namespaced class name of a Singleton class.
	 *
	 * @throws SingletonException
	 */
	public function find(string $class): string {
		if (!isset($this->map[$class])) {
			throw new SingletonException($class);
		}
		$groupId   = $this->map[$class];
		$group     = $this->groups[$groupId];
		$index     = $this->namespaceIndex[$groupId];
		$namespace = $this->namespaces[$index];
		return $namespace . '\\' . $group . '\\' . $class;
	}

	public function add(SingletonGroup $group): SingletonMap {
		$groupId                = count($this->groups);
		$this->groups[]         = $group->getGroup();
		$this->namespaceIndex[] = $this->addNamespace($group->getNamespace());
		foreach ($group->getSingletons() as $singleton) {
			$this->map[$singleton] = $groupId;
		}
		return $this;
	}

	private function addNamespace(string $namespace): int {
		$n = count($this->namespaces);
		for ($i = 0; $i < $n; $i++) {
			if ($this->namespaces[$i] === $namespace) {
				return $i;
			}
		}
		$this->namespaces[] = $namespace;
		return $n;
	}
}
