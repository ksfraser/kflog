<?php

/**
 * Test stub for the legacy write_file dependency.
 * Captures log lines in-memory for assertions.
 */
class write_file
{
    public array $lines = [];

    public function __construct(string $tmp_dir = '.', string $filename = 'log.txt')
    {
        // no-op
    }

    public function write_line(string $line): void
    {
        $this->lines[] = $line;
    }

    public function __destruct()
    {
        // no-op
    }
}
