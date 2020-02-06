<?php

namespace Elgg\Exceptions\Http\Gatekeeper;

use Elgg\Exceptions\HttpException;

/**
 * Thrown when one of the gatekeepers prevents access
 *
 * @since 4.0
 */
class GroupGatekeeperException extends HttpException {

	/**
	 * {@inheritdoc}
	 */
	public function __construct(string $message = "", int $code = 0, \Throwable $previous = null) {
		if (!$message) {
			$message = elgg_echo('membershiprequired');
		}
		if (!$code) {
			$code = ELGG_HTTP_FORBIDDEN;
		}
		parent::__construct($message, $code, $previous);
	}
}
