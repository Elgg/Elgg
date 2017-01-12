<?php

namespace Elgg;

use Throwable;

/**
 * Thrown when page is not accessible
 */
class PageNotFoundException extends HttpException {

	/**
	 * {@inheritdoc}
	 */
	public function __construct(string $message = "", int $code = 0, Throwable $previous = null) {
		if (!$message) {
			$message = elgg_echo('PageNotFoundException');
		}
		if (!$code) {
			$code = ELGG_HTTP_NOT_FOUND;
		}
		parent::__construct($message, $code, $previous);
	}
}
