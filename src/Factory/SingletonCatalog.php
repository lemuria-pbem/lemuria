<?php
declare (strict_types = 1);
namespace Lemuria\Factory;

interface SingletonCatalog
{
	public function getGroups(): array;
}
