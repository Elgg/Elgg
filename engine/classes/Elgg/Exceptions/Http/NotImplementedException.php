<?php

namespace Elgg\Exceptions\Http;

use Elgg\Exceptions\HttpException;

/**
 * Thrown when the server doesn't recognize the request method, or it lacks the ability to fulfil the request
 *
 * @since 6.2
 */
class NotImplementedException extends HttpException {
	
	/**
	 * {@inheritdoc}
	 */
	public function __construct(string $message = '', int $code = 0, \Throwable $previous = null) {
		if (!$message) {
			$message = elgg_echo('NotImplementedException');
		}
		
		if (!$code) {
			$code = ELGG_HTTP_NOT_IMPLEMENTED;
		}
		
		parent::__construct($message, $code, $previous);
	}
}
