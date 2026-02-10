<?php

$autoload = __DIR__ . '/../vendor/autoload.php';
if (file_exists($autoload)) {
    require_once $autoload;
}

// If composer deps aren't installed yet, provide minimal stubs.
// These stubs are only intended for unit tests.
$stubsDir = __DIR__ . '/stubs';
set_include_path($stubsDir . PATH_SEPARATOR . get_include_path());

require_once $stubsDir . '/defines.inc.php';
require_once $stubsDir . '/origin.php';
