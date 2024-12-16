<?php

namespace Elgg\Exceptions\Http;

/**
 * Action validation exception
 *
 * @since 4.0
 */
class ValidationException extends BadRequestException {

	/**
	 * {@inheritdoc}
	 */
	public function __construct(string $message = '', int $code = 0, \Throwable $previous = null) {
		if (!$message) {
			$message = elgg_echo('ValidationException');
		}
		
		parent::__construct($message, $code, $previous);
	}
}
