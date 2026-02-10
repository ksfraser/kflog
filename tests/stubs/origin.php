<?php

declare(strict_types=1);

namespace ksfraser;

/**
 * Minimal stub of the origin base class used by kflog.
 */
class origin
{
    public int $loglevel;

    public function __construct(int $loglevel = PEAR_LOG_DEBUG)
    {
        $this->loglevel = $loglevel;
    }

    public function set(string $field, $value = null, bool $enforce = true)
    {
        $this->{$field} = $value;
        return true;
    }

    public function tell_eventloop(object $caller, string $event, $msg = null): void
    {
        // no-op for tests
    }

    public function build_interested(): void
    {
        // no-op for tests
    }
}
