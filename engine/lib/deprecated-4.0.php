<?php
/**
 * Bundle all functions which have been deprecated in Elgg 4.0
 */

use Elgg\Exceptions\SecurityException;

/**
 * Forward to $location.
 *
 * Sends a 'Location: $location' header and exits.  If headers have already been sent, throws an exception.
 *
 * @param string $location URL to forward to browser to. This can be a path
 *                         relative to the network's URL.
 * @param string $reason   Short explanation for why we're forwarding. Set to
 *                         '404' to forward to error page. Default message is
 *                         'system'.
 *
 * @return void
 * @throws SecurityException
 * @deprecated 4.0
 */
function forward($location = "", $reason = 'system') {
	elgg_deprecated_notice(__METHOD__ . ' has been deprecated, use \Elgg\Exceptions\HttpException or elgg_redirect_response()', '4.0');
	if (headers_sent($file, $line)) {
		throw new SecurityException("Redirect could not be issued due to headers already being sent. Halting execution for security. "
				. "Output started in file $file at line $line. Search http://learn.elgg.org/ for more information.");
	}
	
	_elgg_services()->responseFactory->redirect($location, $reason);
	exit;
}
