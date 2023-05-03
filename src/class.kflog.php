<?php

/******************************************************
* This file is part of the ksfraser packages.         
******************************************************/

namespace ksfraser;

/******************************************************
* 20230423 working to convert from PEAR LOG to monolog
******************************************************/

//!< Dependant upon PEAR LOG

$path_to_root="../..";

//require_once( 'class.origin.php' );
use ksfraser\origin;

//20230503 Repackage using composer
//require_once( dirname( __FILE__ ) . '/../../pear/log/Log.php' );
//@include_once ( 'Log/file.php' );
use pear\log;


require_once( 'class.write_file.php' );
require_once( 'defines.inc.php' );


/*
		PEAR uses the RFC5424 levels
	from Monolog\Level
 * - Use ->getName() to get the standard Monolog name which is full uppercased (e.g. "DEBUG")
 * - Use ->toPsrLogLevel() to get the standard PSR-3 name which is full lowercased (e.g. "debug")
 * - Use ->toRFC5424Level() to get the standard RFC 5424 value (e.g. 7 for debug, 0 for emergency)
 * - Use ->name to get the enum case's name which is capitalized (e.g. "Debug")

  	define( 'PEAR_LOG_EMERG', 0 );
        define( 'PEAR_LOG_ALERT', 1 );
        define( 'PEAR_LOG_CRIT', 2 );
        define( 'PEAR_LOG_ERR', 3 );
        define( 'PEAR_LOG_WARNING', 4 );
        define( 'PEAR_LOG_NOTICE', 5 );
        define( 'PEAR_LOG_INFO', 6 );
        define( 'PEAR_LOG_DEBUG', 7 );
*/


/*******************************************************
*Monolog supports the logging levels described by RFC 5424.
*
*	DEBUG (100): Detailed debug information.
*	INFO (200): Interesting events. Examples: User logs in, SQL logs.
*	NOTICE (250): Normal but significant events.
*	WARNING (300): Exceptional occurrences that are not errors. Examples: Use of deprecated APIs, poor use of an API, undesirable things that are not necessarily wrong.
*	ERROR (400): Runtime errors that do not require immediate action but should typically be logged and monitored.
*	CRITICAL (500): Critical conditions. Example: Application component unavailable, unexpected exception.
*	ALERT (550): Action must be taken immediately. Example: Entire website down, database unavailable, etc. This should trigger the SMS alerts and wake you up.
*	EMERGENCY (600): Emergency: system is unusable.
*******************************************************/
//20230423 Monolog changes
use Monolog\Level;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
//!- 20230423 Monolog changes

/**//****************************************************
* A class to wrap logging actions to tie into my framework.
*
* This class was written to allow my homegrown eventloop
* to log actions to various levels.
*
*********************************************************/
//class kfLog extends origin implements LoggerInterface
class kflog extends origin
{
	var $logobject;
	var $objWriteFile;
	protected $default_filename;
	protected $monolog_level;
	protected $bUseMonolog;
	protected $monolog_logger;

	function __construct( $filename = __FILE__, $level = PEAR_LOG_DEBUG )
	{
		parent::__construct();
		$conf = array();
		$this->default_filename = $filename;
		$this->monolog_level = $level;
		$filename = basename( realpath( $filename ) );
		try
		{
			if( $ret = include_once( 'Log/file.php' ) )
			{
				$this->logobject = new Log_file( $filename . "_debug_log_pear.txt", "", $conf, $level );
			}
			else
			{
				$this->logobject = new stdClass();
			}
		} catch( Exception $e )
		{
				$this->logobject = new stdClass();
		}
		$this->objWriteFile = new write_file( ".", $filename . "_debug_log." . date( 'YmdHis' ) . ".txt" );
		$this->bUseMonolog = false;
		return;	
	}
	function convertLogLevel( $level )
	{
		switch( $level )
		{
			default:
				$this->monolog_level = 0;
				break;
		}
	}
	function initMonolog( $channelName, $loglevel )
	{
		$this->bUseMonolog = true;
			//$log = new Monolog\Logger('name');
			//$log->pushHandler(new Monolog\Handler\StreamHandler('app.log', Monolog\Logger::WARNING));
			//$log->warning('Foo');
		$this->monolog_logger = new Monolog\Logger( $channelName );
		//File fallback
		$this->monolog_logger->pushHandler( new Monolog\Handler\StreamHandler( __DIR__ . "/" . $this->default_filename . "_debug_monolog." . date( 'YmdHis' ) . ".txt", Level::Debug ) );
		$this->monolog_loger->info('Monolog Logger setup');
	}
	function __destruct()
	{
		$this->objWriteFile->__destruct();
	}
	function Log( $msg, $level = PEAR_LOG_DEBUG )
	{
		if( ! isset( $this->loglevel ) )
		{
			$this->set( 'loglevel', PEAR_LOG_CRIT );
			$this->tell_eventloop( $this, "SETTINGS_QUERY", "app_log_level" );
		}
	/*
	 *Not sure why I was unsetting on WARN
		if( strcmp( $level, "WARN" ) == 0 )
		{
			unset( $level );
			//$level = PEAR_LOG_WARN;
		//	$this->logobject->log( $msg, PEAR_LOG_WARN );
		}
	*/
		if( $this->loglevel >= $level )
		{
			$this->objWriteFile->write_line( $level . "//" . $this->loglevel .  "::" . $msg . "\r\n" );
		}
		$this->objWriteFile->write_line( $msg );
		return;	
	}
/*******************************************************
*	EMERGENCY (600): Emergency: system is unusable.
*
*	could also have used logger->log( $level, $msg, $context );
*	but I had already copy/pasted/fix each of these
*******************************************************/
	function log_0( $obj, $msg )
	{
		$this->Log( $msg, PEAR_LOG_EMERG );
		if( $this->bUseMonolog )
		{
			$this->monolog_logger->emergency( $msg );
		}
	}
/*******************************************************
*Monolog supports the logging levels described by RFC 5424.
*	ALERT (550): Action must be taken immediately. Example: Entire website down, database unavailable, etc. This should trigger the SMS alerts and wake you up.
*******************************************************/
	function log_1( $obj, $msg )
	{
		$this->Log( $msg, PEAR_LOG_ALERT );
		if( $this->bUseMonolog )
		{
			$this->monolog_logger->alert( $msg );
		}
	}
/*******************************************************
*Monolog supports the logging levels described by RFC 5424.
*	CRITICAL (500): Critical conditions. Example: Application component unavailable, unexpected exception.
*******************************************************/
	function log_2( $obj, $msg )
	{
		$this->Log( $msg, PEAR_LOG_CRIT );
		if( $this->bUseMonolog )
		{
			$this->monolog_logger->critical( $msg );
		}
	}
/*******************************************************
*Monolog supports the logging levels described by RFC 5424.
*	ERROR (400): Runtime errors that do not require immediate action but should typically be logged and monitored.
*******************************************************/
	function log_3( $obj, $msg )
	{
		$this->Log( $msg, PEAR_LOG_ERR );
		if( $this->bUseMonolog )
		{
			$this->monolog_logger->error( $msg );
		}
	}
/*******************************************************
*Monolog supports the logging levels described by RFC 5424.
*	WARNING (300): Exceptional occurrences that are not errors. Examples: Use of deprecated APIs, poor use of an API, undesirable things that are not necessarily wrong.
*******************************************************/
	function log_4( $obj, $msg )
	{
		$this->Log( $msg, PEAR_LOG_WARNING );
		if( $this->bUseMonolog )
		{
			$this->monolog_logger->warning( $msg );
		}
	}
/*******************************************************
*Monolog supports the logging levels described by RFC 5424.
*	NOTICE (250): Normal but significant events.
*******************************************************/
	function log_5( $obj, $msg )
	{
		$this->Log( $msg, PEAR_LOG_NOTICE );
		if( $this->bUseMonolog )
		{
			$this->monolog_logger->notice( $msg );
		}
	}
/*******************************************************
*Monolog supports the logging levels described by RFC 5424.
*	INFO (200): Interesting events. Examples: User logs in, SQL logs.
*******************************************************/
	function log_6( $obj, $msg )
	{
		$this->Log( $msg, PEAR_LOG_INFO );
		if( $this->bUseMonolog )
		{
			$this->monolog_logger->info( $msg );
		}
	}
/*******************************************************
*Monolog supports the logging levels described by RFC 5424.
*	DEBUG (100): Detailed debug information.
*******************************************************/
	function log_7( $obj, $msg )
	{
		$this->Log( $msg, PEAR_LOG_DEBUG );
		if( $this->bUseMonolog )
		{
			$this->monolog_logger->debug( $msg );
		}
	}
	function build_interested()
	{
		parent::build_interested();
		$this->interestedin['NOTIFY_LOG_DEBUG']['function'] = "log_7";
                $this->interestedin['NOTIFY_LOG_INFO']['function'] = "log_6";
                $this->interestedin['NOTIFY_LOG_NOTICE']['function'] = "log_5";
                $this->interestedin['NOTIFY_LOG_WARNING']['function'] = "log_4";
                $this->interestedin['NOTIFY_LOG_ERR']['function'] = "log_3";
                $this->interestedin['NOTIFY_LOG_CRIT']['function'] = "log_2";
                $this->interestedin['NOTIFY_LOG_ALERT']['function'] = "log_1";
                $this->interestedin['NOTIFY_LOG_EMERG']['function'] = "log_0";
		
	}
}
?>

