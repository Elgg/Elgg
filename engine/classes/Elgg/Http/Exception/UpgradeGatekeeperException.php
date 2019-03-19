<?php

namespace Elgg\Http\Exception;

use Throwable;
use Elgg\GatekeeperException;

/**
 * Thrown when the request to upgrade.php isn't valid
 */
class UpgradeGatekeeperException extends GatekeeperException {
	
	/**
	 * {@inheritdoc}
	 */
	public function __construct(string $message = "", int $code = 0, Throwable $previous = null) {
		if (!$message) {
			$message = elgg_echo('invalid_request_signature');
		}
		if (!$code) {
			$code = ELGG_HTTP_FORBIDDEN;
		}
		parent::__construct($message, $code, $previous);
	}
}
