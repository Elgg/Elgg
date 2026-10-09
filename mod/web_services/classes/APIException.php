<?php

use Elgg\Exceptions\Exception as ElggException;

/**
 * API Exception Stub
 *
 * Generic parent class for API exceptions.
 *
 * @deprecated 7.1 use other Elgg exceptions
 */
class APIException extends ElggException {
	
	/**
	 * {@inheritdoc}
	 */
	public function __construct(string $message = '', int $code = 0, ?Throwable $previous = null) {
		elgg_deprecated_notice(__CLASS__ . ' has been deprecated, use other Elgg exceptions', '7.1');
		
		parent::__construct($message, $code, $previous);
	}
}
