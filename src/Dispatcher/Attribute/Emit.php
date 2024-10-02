<?php
declare(strict_types = 1);
namespace Lemuria\Dispatcher\Attribute;

#[\Attribute(\Attribute::TARGET_FUNCTION | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
readonly class Emit
{
	public final const string ON_BEGIN = 'The event is emitted when the function begins.';

	public final const string ON_RETURN = 'The event is emitted when the function returns.';

	public final const string ON_THROW = 'The event is emitted when the function throws an exception.';

	public function __construct(protected string $event, protected string $when = self::ON_RETURN) {
	}
}
