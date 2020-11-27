<?php
declare(strict_types = 1);
namespace Lemuria\Model;

use Lemuria\Engine\Report;

interface Config
{
	public function Builder(): Builder;

	public function Calendar(): Calendar;

	public function Catalog(): Catalog;

	public function Game(): Game;

	public function Report(): Report;

	public function World(): World;

	public function getPathToLog(): string;
}
