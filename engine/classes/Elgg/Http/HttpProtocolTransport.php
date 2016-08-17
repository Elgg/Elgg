<?php

namespace Elgg\Http;

use Elgg\Http\ResponseTransport;
use Symfony\Component\HttpFoundation\Response;

/**
 * Transport for sending responses to HTTP clients via HTTP protocol
 *
 * @since 2.3
 * @access private
 */
class HttpProtocolTransport implements ResponseTransport {
	
	/**
	 * {@inheritdoc}
	 */
	public function send(Response $response) {
		return $response->send();
	}

}
