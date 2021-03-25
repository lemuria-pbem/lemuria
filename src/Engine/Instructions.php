<?php
declare(strict_types = 1);
namespace Lemuria\Engine;

use Lemuria\Serializable;

interface Instructions extends \ArrayAccess, \Countable, \Iterator, Serializable
{
    /**
     * Clears the instruction list.
     */
    public function clear(): Instructions;
}
