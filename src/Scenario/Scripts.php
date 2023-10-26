<?php
declare(strict_types = 1);
namespace Lemuria\Scenario;

interface Scripts
{
	public function load(): static;

	public function play(): static;

	public function save(): static;
}
