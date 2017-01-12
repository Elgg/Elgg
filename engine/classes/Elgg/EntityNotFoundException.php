<?php

namespace Elgg;

use Throwable;

/**
 * Thrown when entity can not be found
 */
class EntityNotFoundException extends HttpException {

	/**
	 * {@inheritdoc}
	 */
	public function __construct(string $message = "", int $code = 0, Throwable $previous = null) {
		if (!$message) {
			$message = elgg_echo('EntityNotFoundException');
		}
		if (!$code) {
			$code = ELGG_HTTP_NOT_FOUND;
		}
		parent::__construct($message, $code, $previous);
	}
}
