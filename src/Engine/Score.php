<?php
declare(strict_types = 1);
namespace Lemuria\Engine;

use Lemuria\Identifiable;
use Lemuria\Model\Catalog;

interface Score
{
	public const PARTY = Catalog::PARTIES;

	public const UNIT = Catalog::UNITS;

	public const LOCATION = Catalog::LOCATIONS;

	public const CONSTRUCTION = Catalog::CONSTRUCTIONS;

	public const VESSEL = Catalog::VESSELS;

	public function find(Identifiable $effect): Identifiable;

	public function add(Identifiable $effect): Score;

	public function remove(Identifiable $effect): Score;

	/**
	 * Load message data into score.
	 */
	public function load(): Score;

	/**
	 * Save message data from score.
	 */
	public function save(): Score;
}
