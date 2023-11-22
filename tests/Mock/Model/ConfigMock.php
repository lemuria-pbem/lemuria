<?php
declare(strict_types = 1);
namespace Lemuria\Tests\Mock\Model;

use Lemuria\Engine\Debut;
use Lemuria\Engine\Hostilities;
use Lemuria\Engine\Orders;
use Lemuria\Engine\Report;
use Lemuria\Engine\Score;
use Lemuria\Exception\LemuriaException;
use Lemuria\Factory\DefaultBuilder;
use Lemuria\Factory\Namer;
use Lemuria\FeatureFlag;
use Lemuria\Log;
use Lemuria\Model\Builder;
use Lemuria\Model\Calendar;
use Lemuria\Model\Calendar\BaseCalendar;
use Lemuria\Model\Catalog;
use Lemuria\Model\Config;
use Lemuria\Model\Game;
use Lemuria\Model\World;
use Lemuria\Registry;
use Lemuria\Scenario\Scripts;
use Lemuria\Statistics;
use Lemuria\Tests\Mock\Engine\DebutMock;
use Lemuria\Tests\Mock\Engine\HostilitiesMock;
use Lemuria\Tests\Mock\Engine\OrdersMock;
use Lemuria\Tests\Mock\Engine\ReportMock;
use Lemuria\Tests\Mock\Engine\ScoreMock;
use Lemuria\Tests\Mock\Factory\NamerMock;
use Lemuria\Tests\Mock\LogMock;
use Lemuria\Tests\Mock\RegistryMock;
use Lemuria\Tests\Mock\Scenario\ScriptsMock;
use Lemuria\Tests\Mock\StatisticsMock;

class ConfigMock implements Config
{
	public function Builder(): Builder {
		return new DefaultBuilder();
	}

	public function Catalog(): Catalog {
		return new CatalogMock();
	}

	public function Calendar(): Calendar {
		return new BaseCalendar();
	}

	public function Debut(): Debut {
		return new DebutMock();
	}

	public function Game(): Game {
		return new GameMock();
	}

	public function Orders(): Orders {
		return new OrdersMock();
	}

	public function Report(): Report {
		return new ReportMock();
	}

	public function World(): World {
		return new WorldMock();
	}

	public function Score(): Score {
		return new ScoreMock();
	}

	public function Statistics(): Statistics {
		return new StatisticsMock();
	}

	public function Hostilities(): Hostilities {
		return new HostilitiesMock();
	}

	public function Registry(): Registry {
		return new RegistryMock();
	}

	public function Log(): Log {
		return new LogMock();
	}

	public function FeatureFlag(): FeatureFlag {
		return new FeatureFlag();
	}

	public function Namer(): Namer {
		return new NamerMock();
	}

	public function Scripts(): ?Scripts {
		return new ScriptsMock();
	}

	public function getStoragePath(): string {
		throw new LemuriaException('Not implemented in ConfigMock.');
	}
}
