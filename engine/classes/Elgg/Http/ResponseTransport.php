<?php

namespace Elgg\Http;

use Symfony\Component\HttpFoundation\Response;

/**
 * HTTP response transport interface
 *
 * @since 2.3
 * @access private
 */
interface ResponseTransport {

	/**
	 * Sends HTTP response to the requester
	 *
	 * @param Response $response Symfony Response
	 * @return bool
	 */
	public function send(Response $response);

}
