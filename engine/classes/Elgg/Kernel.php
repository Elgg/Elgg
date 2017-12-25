<?php

namespace Elgg;

use Elgg\Application\CacheHandler;
use Elgg\Application\ServeFileHandler;
use WideImage\Exception\Exception;

/**
 * Application Kernel
 */
abstract class Kernel {

	/**
	 * @var Application
	 */
	protected $application;

	/**
	 * @var CacheHandler
	 */
	protected $cache_handler;

	/**
	 * @var ServeFileHandler
	 */
	protected $serve_file_handler;

	/**
	 * Kernel constructor.
	 *
	 * @param Application      $application        Application
	 * @param CacheHandler     $cache_handler      Cache handler
	 * @param ServeFileHandler $serve_file_handler Serve file handler
	 */
	public function __construct(Application $application, CacheHandler $cache_handler, ServeFileHandler $serve_file_handler) {
		$this->application = $application;
		$this->cache_handler = $cache_handler;
		$this->serve_file_handler = $serve_file_handler;
	}

	/**
	 * Runs the application
	 * @return bool
	 */
	abstract public function run();

	/**
	 * Test if rewrite rules are working
	 * @return mixed
	 */
	abstract public function testRewriteRules();

	/**
	 * Redirect the application to another URL
	 *
	 * @param string $url    URL/path to redirect to
	 * @param string $reason Redirect reason
	 *
	 * @return mixed
	 */
	abstract public function redirect($url, $reason = '');

	/**
	 * Sets an error handler
	 *
	 * @param callable|null $handler Handler
	 *
	 * @return void
	 */
	public function setErrorHandler(callable $handler) {
		set_error_handler($handler);
	}

	/**
	 * Sets an exception handler
	 *
	 * @param callable|null $handler Handler
	 *
	 * @return void
	 */
	public function setExceptionHandler(callable $handler) {
		set_exception_handler($handler);
	}

	/**
	 * Registers a shutdown function
	 *
	 * @param callable $handler Handler
	 *
	 * @return void
	 */
	public function registerShutdownFunction(callable $handler) {
		register_shutdown_function($handler);
	}
}
