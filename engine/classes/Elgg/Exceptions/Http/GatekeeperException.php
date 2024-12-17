<?php

namespace Elgg\Exceptions\Http;

/**
 * Thrown when one of the gatekeepers prevents access
 *
 * @since 4.0
 */
class GatekeeperException extends UnauthorizedException {

	/**
	 * {@inheritdoc}
	 */
	public function __construct(string $message = '', int $code = 0, ?\Throwable $previous = null) {
		if (!$message) {
			$message = elgg_echo('GatekeeperException');
		}
		
		parent::__construct($message, $code, $previous);
	}
}
