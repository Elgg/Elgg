<?php

namespace Elgg\Exceptions\Http\Gatekeeper;

/**
 * Thrown when the requested group tool isn't enabled for a group
 *
 * @since 4.0
 */
class GroupToolGatekeeperException extends GroupGatekeeperException {
	
	/**
	 * {@inheritdoc}
	 */
	public function __construct(string $message = "", int $code = 0, \Throwable $previous = null) {
		if (!$message) {
			$message = elgg_echo('groups:tool_gatekeeper');
		}
		
		parent::__construct($message, $code, $previous);
	}
}
