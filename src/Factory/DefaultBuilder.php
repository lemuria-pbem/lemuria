<?php
declare (strict_types = 1);
namespace Lemuria\Factory;

use Lemuria\Exception\LemuriaException;
use Lemuria\Exception\SingletonException;
use Lemuria\Model\Builder;
use Lemuria\Singleton;

/**
 * A builder creates singleton objects.
 */
readonly class DefaultBuilder implements Builder
{
	private SingletonCache $singletonCache;

	private SingletonMap $singletonMap;

	public function __construct() {
		$this->singletonCache = new SingletonCache();
		$this->singletonMap   = new SingletonMap();
	}

	/**
	 * Create a singleton.
	 *
	 * @throws LemuriaException
	 */
	public function create(string $class): Singleton {
		$withNamespace = strrpos($class, '\\');
		if ($withNamespace) {
			$class = substr($class, ++$withNamespace);
		}

		$singleton = $this->singletonCache->get($class);
		if ($singleton) {
			return $singleton;
		}

		$singletonClass = $this->singletonMap->find($class);
		$singleton      = new $singletonClass();
		if ($singleton instanceof Singleton) {
			return $this->singletonCache->set($singleton);
		}

		$bug = 'Class SingletonMap created an invalid Singleton.';
		throw new LemuriaException($bug, new SingletonException($class));
	}

	public function register(SingletonCatalog $catalog): static {
		foreach ($catalog->getGroups() as $group) {
			$this->singletonMap->add($group);
		}
		return $this;
	}
}
