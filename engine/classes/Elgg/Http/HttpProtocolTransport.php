<?php

namespace Elgg\Http;

use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

/**
 * Transport for sending responses to HTTP clients via HTTP protocol
 *
 * @since 2.3
 * @internal
 */
class HttpProtocolTransport implements ResponseTransport {
	
	/**
	 * {@inheritdoc}
	 */
	public function send(SymfonyResponse $response) {
		if (!$response->headers->hasCacheControlDirective('no-cache')) {
			$response->headers->addCacheControlDirective('no-cache', 'Set-Cookie');
		}
		
		$response->send();
		return true;
	}

}
