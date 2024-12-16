<?php

namespace Elgg\Exceptions\Http;

use Elgg\Exceptions\HttpException;

/**
 * Thrown when the server cannot handle the request (because it is overloaded or down for maintenance)
 *
 * @since 6.2
 */
class ServiceUnavailableException extends HttpException {
	
	/**
	 * {@inheritdoc}
	 */
	public function __construct(string $message = '', int $code = 0, \Throwable $previous = null) {
		if (!$message) {
			$message = elgg_echo('ServiceUnavailableException');
		}
		
		if (!$code) {
			$code = ELGG_HTTP_SERVICE_UNAVAILABLE;
		}
		
		parent::__construct($message, $code, $previous);
	}
}
