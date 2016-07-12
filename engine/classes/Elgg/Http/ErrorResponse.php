<?php

namespace Elgg\Http;

/**
 * Error response builder
 */
class ErrorResponse extends OkResponse {

	/**
	 * Constructor
	 * 
	 * @param string $error       Error message
	 * @param int    $status_code HTTP status code
	 * @param string $forward_url Forward url
	 * @access private
	 * @see elgg_error_response
	 */
	public function __construct($error = '', $status_code = ELGG_HTTP_OK, $forward_url = REFERRER) {
		parent::__construct($error, $status_code, $forward_url);
	}
}
