<?php

namespace Elgg\Page;

/**
 * Sets a header
 *
 * @since 4.0
 */
class SetXFrameOptionsHeaderHandler {
	
	/**
	 * Sends X-Frame-Options header on page requests
	 *
	 * @param \Elgg\Hook $hook 'output:before', 'page'
	 *
	 * @return void
	 */
	public function __invoke(\Elgg\Hook $hook) {
		elgg_set_http_header('X-Frame-Options: SAMEORIGIN');
	}
}
