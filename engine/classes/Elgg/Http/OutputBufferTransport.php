<?php

namespace Elgg\Http;

use Elgg\Http\ResponseTransport;
use Symfony\Component\HttpFoundation\Response;

/**
 * Transport for sending responses to non-HTTP clients, e.g. CLI applications, via output buffer
 *
 * @since 2.3
 * @access private
 */
class OutputBufferTransport implements ResponseTransport {

	/**
	 * {@inheritdoc}
	 */
	public function send(Response $response) {
		echo $response->getContent();
		return true;
	}

}
