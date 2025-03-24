<?php

namespace Elgg\Exceptions\Http;

use Elgg\Exceptions\HttpException;

/**
 * Thrown when the client doesn't have access to the requested resource
 *
 * @since 6.2
 */
class ForbiddenException extends HttpException {
	
	/**
	 * {@inheritdoc}
	 */
	public function __construct(string $message = '', int $code = 0, ?\Throwable $previous = null) {
		if (!$message) {
			$message = elgg_echo('ForbiddenException');
		}
		
		if (!$code) {
			$code = ELGG_HTTP_FORBIDDEN;
		}
		
		parent::__construct($message, $code, $previous);
	}
}
