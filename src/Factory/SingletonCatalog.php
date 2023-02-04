<?php
declare (strict_types = 1);
namespace Lemuria\Factory;

interface SingletonCatalog
{
	/**
	 * @return array<SingletonGroup>
	 */
	public function getGroups(): array;
}
