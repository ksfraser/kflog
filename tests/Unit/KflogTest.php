<?php

declare(strict_types=1);

namespace ksfraser\kflog\Tests;

use ksfraser\kflog;
use PHPUnit\Framework\TestCase;

final class KflogTest extends TestCase
{
    public function testLogWritesToWriteFileStub(): void
    {
        $log = new kflog(__FILE__, PEAR_LOG_DEBUG);

        $log->Log('hello', PEAR_LOG_DEBUG);

        $this->assertNotEmpty($log->objWriteFile->lines);
        $this->assertSame('hello', $log->objWriteFile->lines[array_key_last($log->objWriteFile->lines)]);
    }

    public function testLogLevelHandlerMethodsCallLog(): void
    {
        $log = new kflog(__FILE__, PEAR_LOG_DEBUG);

        $log->log_7($this, 'debug');
        $log->log_6($this, 'info');
        $log->log_3($this, 'error');

        $joined = implode("\n", $log->objWriteFile->lines);
        $this->assertStringContainsString('debug', $joined);
        $this->assertStringContainsString('info', $joined);
        $this->assertStringContainsString('error', $joined);
    }
}
