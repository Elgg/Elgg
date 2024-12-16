<?php

namespace Elgg\Exceptions\Http;

/**
 * Thrown when entity can not be edited or container permissions do not allow it to be written
 *
 * @since 4.0
 */
class EntityPermissionsException extends ForbiddenException {

	/**
	 * {@inheritdoc}
	 */
	public function __construct(string $message = '', int $code = 0, \Throwable $previous = null) {
		if (!$message) {
			$message = elgg_echo('EntityPermissionsException');
		}
		
		parent::__construct($message, $code, $previous);
	}
}
