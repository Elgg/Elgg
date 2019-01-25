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
	 * @see elgg_error_response()
	 */
	public function __construct($error = '', $status_code = ELGG_HTTP_BAD_REQUEST, $forward_url = REFERRER) {
		if (isset($status_code) && (!is_numeric($status_code) || $status_code < 100 || $status_code > 599)) {
			$status_code = ELGG_HTTP_INTERNAL_SERVER_ERROR;
		}
		
		parent::__construct($error, $status_code, $forward_url);
	}
}
