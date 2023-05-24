<?php
declare (strict_types = 1);
namespace Lemuria\Tests\Model;

use PHPUnit\Framework\Attributes\Test;

use Lemuria\Model\Domain;

use Lemuria\Tests\Base;

class DomainTest extends Base
{
	#[Test]
	public function isLegacyIsFalseForCurrentValues(): void {
		foreach (Domain::cases() as $domain) {
			$this->assertFalse(Domain::isLegacy($domain->value));
		}
	}

	#[Test]
	public function isLegacyIsTrueForLegacyValues(): void {
		foreach (Domain::cases() as $domain) {
			$this->assertTrue(Domain::isLegacy(100 * $domain->value));
		}
	}

	#[Test]
	public function isLegacyAcceptsOtherValues(): void {
		$this->assertFalse(Domain::isLegacy(123));
	}

	#[Test]
	public function fromLegacyAcceptsCurrentValues(): void {
		foreach (Domain::cases() as $domain) {
			$this->assertSame($domain, Domain::fromLegacy($domain->value));
		}
	}

	#[Test]
	public function fromLegacyAcceptsLegacyValues(): void {
		foreach (Domain::cases() as $domain) {
			$this->assertSame($domain, Domain::fromLegacy(100 * $domain->value));
		}
	}

	#[Test]
	public function fromLegacyRejectsOtherValues(): void {
		$this->expectException(\ValueError::class);
		Domain::fromLegacy(123);
	}

	#[Test]
	public function getLegacyValue(): void {
		foreach (Domain::cases() as $domain) {
			$this->assertSame(100 * $domain->value, $domain->getLegacyValue());
		}
	}
}
