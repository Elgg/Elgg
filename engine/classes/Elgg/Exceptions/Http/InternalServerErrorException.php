<?php

namespace Elgg\Exceptions\Http;

use Elgg\Exceptions\HttpException;

/**
 * Thrown when the server encountered a generic error
 *
 * @since 6.2
 */
class InternalServerErrorException extends HttpException {
	
	/**
	 * {@inheritdoc}
	 */
	public function __construct(string $message = '', int $code = 0, \Throwable $previous = null) {
		if (!$message) {
			$message = elgg_echo('InternalServerErrorException');
		}
		
		if (!$code) {
			$code = ELGG_HTTP_INTERNAL_SERVER_ERROR;
		}
		
		parent::__construct($message, $code, $previous);
	}
}
