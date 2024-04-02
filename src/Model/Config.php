<?php
declare(strict_types = 1);
namespace Lemuria\Model;

use Lemuria\Engine\Debut;
use Lemuria\Engine\Hostilities;
use Lemuria\Engine\Orders;
use Lemuria\Engine\Report;
use Lemuria\Engine\Score;
use Lemuria\Factory\Namer;
use Lemuria\FeatureFlag;
use Lemuria\Log;
use Lemuria\Registry;
use Lemuria\Scenario\Scripts;
use Lemuria\Statistics;

interface Config
{
	public function Locale(): string;

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

	public function Statistics(): Statistics;

	public function Log(): Log;

	public function FeatureFlag(): FeatureFlag;

	public function Namer(): Namer;

	public function Scripts(): ?Scripts;
}
