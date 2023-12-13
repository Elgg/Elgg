<?php

namespace Elgg\Http;

/**
 * Redirect response builder
 */
class RedirectResponse extends Response {

	/**
	 * Constructor
	 *
	 * @param string $forward_url        Forward url
	 * @param int    $status_code        HTTP status code
	 * @param bool   $secure_forward_url If true the forward url will be validated to be an on-site url
	 *
	 * @see elgg_redirect_response()
	 */
	public function __construct(string $forward_url = REFERRER, int $status_code = ELGG_HTTP_FOUND, bool $secure_forward_url = true) {
		$this->secure_forward_url = $secure_forward_url;
		
		$this->setForwardURL($forward_url);
		$this->setStatusCode($status_code);
	}
}
