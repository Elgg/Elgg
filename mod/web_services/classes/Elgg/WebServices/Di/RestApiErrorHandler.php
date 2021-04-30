<?php

namespace Elgg\WebServices\Di;

use Elgg\Traits\Di\ServiceFacade;
use Monolog\Handler\AbstractHandler;
use Monolog\Formatter\LineFormatter;

/**
 * Custom error logger during rest api calls. Stores errors in memory for later use in api result
 *
 * @since 4.0
 */
class RestApiErrorHandler extends AbstractHandler {

	use ServiceFacade;

	/**
	 * @var string[]
	 */
	protected $errors = [];
	
	/**
	 * {@inheritDoc}
	 */
	public function handle(array $record) {
		$this->errors[] = $this->getFormatter()->format($record);
		
		return false;
	}
	
	/**
	 * {@inheritDoc}
	 */
	protected function getDefaultFormatter() {
		return new LineFormatter(null, 'Y-m-d H:i:s (T)', true, true);
	}
	
	/**
	 * Return all the logged errors
	 *
	 * @return string[]
	 */
	public function getErrors() {
		return $this->errors;
	}
	
	/**
	 * Returns registered service name
	 *
	 * @return string
	 */
	public static function name() {
		return 'webservices.rest.error_handler';
	}
}
