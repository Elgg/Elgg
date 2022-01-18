<?php

namespace Elgg\Exceptions\Seeding;

use Elgg\Exceptions\Exception;

/**
 * Thrown when the seeding has exceeded the max attempts for trying to create an \ElggEntity
 *
 * @since 4.2
 */
class MaxAttemptsException extends Exception {
	
}
