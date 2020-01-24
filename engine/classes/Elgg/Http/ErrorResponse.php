<?php

namespace Elgg\Http;

/**
 * Error response builder
 */
class ErrorResponse extends Response {

	/**
	 * Constructor
	 *
	 * @param mixed  $error       Error message / content
	 * @param int    $status_code HTTP status code
	 * @param string $forward_url Forward url
	 *
	 * @see elgg_error_response()
	 */
	public function __construct($error = '', int $status_code = ELGG_HTTP_BAD_REQUEST, $forward_url = REFERRER) {
		if ($status_code < 100 || $status_code > 599) {
			$status_code = ELGG_HTTP_INTERNAL_SERVER_ERROR;
		}
		
		$this->setContent($error);
		$this->setStatusCode($status_code);
		$this->setForwardURL($forward_url);
	}
}
