<?php

namespace Elgg\Http;

/**
 * Redirect response builder
 */
class RedirectResponse extends Response {

	/**
	 * Constructor
	 *
	 * @param string $forward_url Forward url
	 * @param int    $status_code HTTP status code
	 *
	 * @see elgg_redirect_response()
	 */
	public function __construct($forward_url = REFERRER, int $status_code = ELGG_HTTP_FOUND) {
		$this->setForwardURL($forward_url);
		$this->setStatusCode($status_code);
	}
}
