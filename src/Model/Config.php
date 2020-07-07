<?php
declare(strict_types = 1);
namespace Lemuria\Model;

interface Config
{
	/**
	 * @return Builder
	 */
	public function Builder(): Builder;

	/**
	 * @return Calendar
	 */
	public function Calendar(): Calendar;

	/**
	 * @return Catalog
	 */
	public function Catalog(): Catalog;

	/**
	 * @return Game
	 */
	public function Game(): Game;

	/**
	 * @return World
	 */
	public function World(): World;

	/**
	 * @return string
	 */
	public function getPathToLog(): string;
}
