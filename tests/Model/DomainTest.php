<?php
declare (strict_types = 1);
namespace Lemuria\Tests\Model;

use Lemuria\Model\Domain;

use Lemuria\Tests\Test;

class DomainTest extends Test
{
	/**
	 * @test
	 */
	public function isLegacyIsFalseForCurrentValues(): void {
		foreach (Domain::cases() as $domain) {
			$this->assertFalse(Domain::isLegacy($domain->value));
		}
	}

	/**
	 * @test
	 */
	public function isLegacyIsTrueForLegacyValues(): void {
		foreach (Domain::cases() as $domain) {
			$this->assertTrue(Domain::isLegacy(100 * $domain->value));
		}
	}

	/**
	 * @test
	 */
	public function isLegacyAcceptsOtherValues(): void {
		$this->assertFalse(Domain::isLegacy(123));
	}

	/**
	 * @test
	 */
	public function fromLegacyAcceptsCurrentValues(): void {
		foreach (Domain::cases() as $domain) {
			$this->assertSame($domain, Domain::fromLegacy($domain->value));
		}
	}

	/**
	 * @test
	 */
	public function fromLegacyAcceptsLegacyValues(): void {
		foreach (Domain::cases() as $domain) {
			$this->assertSame($domain, Domain::fromLegacy(100 * $domain->value));
		}
	}

	/**
	 * @test
	 */
	public function fromLegacyRejectsOtherValues(): void {
		$this->expectError();
		Domain::fromLegacy(123);
	}

	/**
	 * @test
	 */
	public function getLegacyValue(): void {
		foreach (Domain::cases() as $domain) {
			$this->assertSame(100 * $domain->value, $domain->getLegacyValue());
		}
	}
}
