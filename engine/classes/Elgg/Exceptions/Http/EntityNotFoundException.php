<?php

namespace Elgg\Exceptions\Http;

/**
 * Thrown when entity can not be found
 *
 * @since 4.0
 */
class EntityNotFoundException extends PageNotFoundException {

	/**
	 * {@inheritdoc}
	 */
	public function __construct(string $message = '', int $code = 0, ?\Throwable $previous = null) {
		if (!$message) {
			$message = elgg_echo('EntityNotFoundException');
		}
		
		parent::__construct($message, $code, $previous);
	}
}
