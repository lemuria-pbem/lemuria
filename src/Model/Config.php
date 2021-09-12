<?php
declare(strict_types = 1);
namespace Lemuria\Model;

use Lemuria\Engine\Debut;
use Lemuria\Engine\Hostilities;
use Lemuria\Engine\Orders;
use Lemuria\Engine\Report;
use Lemuria\Engine\Score;
use Lemuria\Log;
use Lemuria\Registry;

interface Config
{
	public function Builder(): Builder;

	public function Calendar(): Calendar;

	public function Catalog(): Catalog;

	public function Debut(): Debut;

	public function Game(): Game;

	public function Orders(): Orders;

	public function Report(): Report;

	public function World(): World;

	public function Score(): Score;

	public function Hostilities(): Hostilities;

	public function Registry(): Registry;

	public function Log(): Log;
}
