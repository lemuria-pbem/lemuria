<?php
declare(strict_types = 1);
namespace Lemuria\Tests\Storage;

use PHPUnit\Framework\Attributes\Test;
use SATHub\PHPUnit\Base;

use Lemuria\Exception\IniFileException;
use Lemuria\Storage\Ini\Section;
use Lemuria\Storage\Ini\Value;
use Lemuria\Storage\IniProvider;

class IniProviderTest extends Base
{
	private const DIR = __DIR__ . '/../storage/scripts';

	private const EMPTY = 'empty.ini';

	private const EMPTY_SECTION = 'empty-section.ini';

	private const NO_SECTION = 'no-section.ini';

	private const ONE = 'one.ini';

	private const THREE = 'three.ini';

	private const UNIT = 'unit.ini';

	#[Test]
	public function emptyIni(): void {
		$provider = new IniProvider(self::DIR);

		$this->assertTrue($provider->exists(self::EMPTY));

		$sectionList = $provider->read(self::EMPTY);

		$this->assertSame(0, $sectionList->count());
	}

	#[Test]
	public function emptySection(): void {
		$provider = new IniProvider(self::DIR);

		$this->assertTrue($provider->exists(self::EMPTY_SECTION));
		$this->expectException(IniFileException::class);

		$provider->read(self::EMPTY_SECTION);
	}

	#[Test]
	public function noSection(): void {
		$provider = new IniProvider(self::DIR);

		$this->assertTrue($provider->exists(self::NO_SECTION));
		$this->expectException(IniFileException::class);

		$provider->read(self::NO_SECTION);
	}

	#[Test]
	public function oneSection(): void {
		$provider = new IniProvider(self::DIR);

		$this->assertTrue($provider->exists(self::ONE));

		$sectionList = $provider->read(self::ONE);

		$this->assertSame(1, $sectionList->count());

		$section = $sectionList->current();

		$this->assertInstanceOf(Section::class, $section);
		$this->assertSame('one section', $section->Name());
		$this->assertSame(2, $section->Lines()->count());
		$this->assertSame('This is just=text.', $section->Lines()->current());
		$section->Lines()->next();
		$this->assertSame('In a simple line ; is not a comment.', $section->Lines()->current());
		$this->assertSame(2, $section->Values()->count());
		$this->assertTrue($section->Values()->offsetExists('Key Name'));
		$this->assertSame('Value with = character and ; character.', (string)$section->Values()['Key Name']);
		$this->assertTrue($section->Values()->offsetExists('Second = Key'));
		$this->assertSame('Simple_Value', (string)$section->Values()['Second = Key']);
	}

	#[Test]
	public function threeSections(): void {
		$provider = new IniProvider(self::DIR);

		$this->assertTrue($provider->exists(self::THREE));

		$sectionList = $provider->read(self::THREE);

		$this->assertSame(3, $sectionList->count());

		$section = $sectionList->current();

		$this->assertInstanceOf(Section::class, $section);
		$this->assertSame('Einheit', $section->Name());
		$this->assertSame(0, $section->Lines()->count());
		$this->assertSame(4, $section->Values()->count());
		$this->assertTrue($section->Values()->offsetExists('Name'));
		$this->assertSame('Galbrak, der Fahrende', (string)$section->Values()['Name']);

		$sectionList->next();
		$section = $sectionList->current();

		$this->assertInstanceOf(Section::class, $section);
		$this->assertSame('Skript Temp h', $section->Name());
		$this->assertSame(4, $section->Lines()->count());
		$this->assertSame('VORGABE Wiederholen', $section->Lines()->current());
		$this->assertSame(1, $section->Values()->count());
		$this->assertTrue($section->Values()->offsetExists('Runde'));
		$this->assertSame('129', (string)$section->Values()['Runde']);

		$sectionList->next();
		$section = $sectionList->current();

		$this->assertInstanceOf(Section::class, $section);
		$this->assertSame('Skript Temp i', $section->Name());
		$this->assertSame(2, $section->Lines()->count());
		$this->assertSame('Market(3)', $section->Lines()->current());
		$section->Lines()->next();
		$this->assertSame('Rundreise(u, v, 1i, 1q, 18)', $section->Lines()->current());
		$this->assertSame(0, $section->Values()->count());
	}

	#[Test]
	public function unit(): void {
		$provider = new IniProvider(self::DIR);

		$this->assertTrue($provider->exists(self::UNIT));

		$sectionList = $provider->read(self::UNIT);

		$this->assertSame(1, $sectionList->count());

		$section = $sectionList->current();

		$this->assertInstanceOf(Section::class, $section);
		$this->assertSame('Einheit', $section->Name());
		$this->assertSame(0, $section->Lines()->count());
		$this->assertSame(6, $section->Values()->count());
		$this->assertTrue($section->Values()->offsetExists('Name'));

		$value = $section->Values()['Name'];

		$this->assertInstanceOf(Value::class, $value);
		$this->assertSame(1, $value->count());
		$this->assertTrue('Galbrak' == $value);

		$value = $section->Values()['Talent'];

		$this->assertInstanceOf(Value::class, $value);
		$this->assertSame(2, $value->count());
		$this->assertSame('Reiten 2', (string)$value);

		$value->rewind();
		$this->assertSame('Handeln 5', $value->current());
		$value->next();
		$this->assertSame('Reiten 2', $value->current());
		$value->next();
		$this->assertFalse($value->valid());
	}
}
