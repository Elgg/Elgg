<?php

namespace Elgg\Http\Exception;

use Throwable;
use Elgg\BadRequestException;

/**
 * Thrown when the request is not a valid ajax request
 */
class AjaxGatekeeperException extends BadRequestException {
	
	/**
	 * {@inheritdoc}
	 */
	public function __construct(string $message = "", int $code = 0, Throwable $previous = null) {
		if (!$message) {
			$message = elgg_echo('ajax:not_is_xhr');
		}
		
		parent::__construct($message, $code, $previous);
	}
}
