<?php

namespace Elgg\Http;

use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

/**
 * HTTP response transport interface
 *
 * @since 2.3
 * @internal
 */
interface ResponseTransport {

	/**
	 * Sends HTTP response to the requester
	 *
	 * @param SymfonyResponse $response Symfony Response
	 * @return bool
	 */
	public function send(SymfonyResponse $response);

}
