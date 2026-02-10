<?php

// Minimal PEAR log level constants used by kflog.
if (!defined('PEAR_LOG_EMERG')) {
    define('PEAR_LOG_EMERG', 0);
    define('PEAR_LOG_ALERT', 1);
    define('PEAR_LOG_CRIT', 2);
    define('PEAR_LOG_ERR', 3);
    define('PEAR_LOG_WARNING', 4);
    define('PEAR_LOG_NOTICE', 5);
    define('PEAR_LOG_INFO', 6);
    define('PEAR_LOG_DEBUG', 7);
}
