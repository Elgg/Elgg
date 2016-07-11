<?php

namespace Elgg\Http;

/**
 * Redirect response builder
 */
class RedirectResponse extends OkResponse {

	/**
	 * Constructor
	 *
	 * @param string $forward_url Forward url
	 * @param int    $status_code HTTP status code
	 * @access private
	 * @see elgg_redirect_response
	 */
	public function __construct($forward_url = REFERRER, $status_code = ELGG_HTTP_FOUND) {
		parent::__construct('', $status_code, $forward_url);
	}
}
