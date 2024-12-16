<?php

namespace Elgg\Exceptions\Http;

use Elgg\Exceptions\HttpException;

/**
 * Thrown when request was made with an unsupported method for the resource
 *
 * @since 6.2
 */
class MethodNotAllowedException extends HttpException {
	
	/**
	 * {@inheritdoc}
	 */
	public function __construct(string $message = '', int $code = 0, \Throwable $previous = null) {
		if (!$message) {
			$message = elgg_echo('MethodNotAllowedException');
		}
		
		if (!$code) {
			$code = ELGG_HTTP_METHOD_NOT_ALLOWED;
		}
		
		parent::__construct($message, $code, $previous);
	}
}
