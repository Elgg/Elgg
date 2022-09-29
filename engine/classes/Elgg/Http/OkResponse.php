<?php

namespace Elgg\Http;

/**
 * OK response builder
 */
class OkResponse extends Response {
	
	/**
	 * Constructor
	 *
	 * @param mixed  $content     Response data
	 * @param int    $status_code HTTP status code
	 * @param string $forward_url Forward URL
	 *
	 * @see elgg_ok_response()
	 */
	public function __construct($content = '', int $status_code = ELGG_HTTP_OK, string $forward_url = null) {
		$this->setContent($content);
		$this->setStatusCode($status_code);
		
		if (isset($forward_url)) {
			$this->setForwardURL($forward_url);
		}
	}
}
