<?php

namespace Elgg\Exceptions\Http;

use Elgg\Exceptions\HttpException;

/**
 * Thrown when the requested resource is no longer available and will not be available again
 *
 * @since 6.2
 */
class GoneException extends HttpException {
	
	/**
	 * {@inheritdoc}
	 */
	public function __construct(string $message = '', int $code = 0, \Throwable $previous = null) {
		if (!$message) {
			$message = elgg_echo('GoneException');
		}
		
		if (!$code) {
			$code = ELGG_HTTP_GONE;
		}
		
		parent::__construct($message, $code, $previous);
	}
}
