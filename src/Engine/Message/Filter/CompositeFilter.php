<?php
declare(strict_types = 1);
namespace Lemuria\Engine\Message\Filter;

use Lemuria\Engine\Message;
use Lemuria\Engine\Message\Filter;

/**
 * This filter allows composition of multiple filters.
 */
class CompositeFilter implements Filter
{
	/**
	 * @var array<Filter>
	 */
	protected array $filters = [];

	public function retains(Message $message): bool {
		foreach ($this->filters as $filter) {
			if ($filter->retains($message)) {
				return true;
			}
		}
		return false;
	}

	public function add(Filter $filter): CompositeFilter {
		$this->filters[] = $filter;
		return $this;
	}
}
