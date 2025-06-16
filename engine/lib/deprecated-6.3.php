<?php
/**
 * Bundle all functions that have been deprecated in Elgg 6.3
 */

/**
 * Logs $value to PHP's {@link error_log()}
 *
 * A 'debug', log' event is triggered. If a handler returns
 * false, it will stop the default logging method.
 *
 * @note Use the developers plugin to display logs
 *
 * @param mixed $value The value
 * @return void
 * @since 1.7.0
 * @deprecated 6.3 Use elgg_log()
 */
function elgg_dump($value): void {
	elgg_deprecated_notice(__METHOD__ . ' has been deprecated, use elgg_log()', '6.3');
	
	_elgg_services()->logger->error($value);
}
