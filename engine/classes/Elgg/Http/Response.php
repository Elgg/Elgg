<?php

namespace Elgg\Http;

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 * 
 * @since 2.2.0
 * @access private
 */
class Response {

	/**
	 * @var \Elgg\Http\Request
	 */
	private $request;

	/**
	 * @var \Elgg\PluginHooksService
	 */
	private $hooks;

	public function __construct(\Elgg\Http\Request $request, \Elgg\PluginHooksService $hooks) {
		$this->request = $request;
		$this->hooks = $hooks;
	}
	
	/**
	 * Creates an HTTP response
	 *
	 * @param string  $content The response content
	 * @param integer $status  The response status code
	 * @param array   $headers An array of response headers
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function prepareResponse($content = '', $status = 200, array $headers = array()) {
		$response = new \Symfony\Component\HttpFoundation\Response($content, $status, $headers);
		$response->prepare($this->request);
		return $response;
	}

	/**
	 * Creates an HTTP json response
	 *
	 * @param string  $data    Data to send
	 * @param integer $status  The response status code
	 * @param array   $headers An array of response headers
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function prepareJsonResponse($data, $status = 200, array $headers = array()) {
		$response = new \Symfony\Component\HttpFoundation\JsonResponse($data, $status, $headers);
		$response->prepare($this->request);
		return $response;
	}

	/**
	 * Creates a redirect response
	 *
	 * @param string  $url     URL to redirect to
	 * @param integer $status  The status code (302 by default)
	 * @param array   $headers An array of response headers (Location is always set to the given URL)
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function prepareRedirectResponse($url, $status = 302, array $headers = array()) {
		return new \Symfony\Component\HttpFoundation\RedirectResponse($url, $status, $headers);
	}

	/**
	 * Prepares a redirect response
	 *
	 * @param string $location URL to forward to browser to.
	 *                         This can be a path relative to the network's URL.
	 * @param string $reason   Short explanation for why we're forwarding. 
	 *                         Set to '404' to forward to error page.
	 *                         Default message is 'system'.
	 * @param bool   $send     Immediately send the response. Can be set to false for testing purposes.
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|void
	 * @throws \SecurityException
	 */
	public function forward($location = '', $reason = null, $send = null) {
		if (!isset($reason)) {
			$reason = 'system';
		}
		if (!isset($send)) {
			$send = !elgg_get_config('Elgg\Application_phpunit');
		}
		if (!headers_sent($file, $line)) {
			if ($location === REFERER) {
				$location = $this->request->headers->get('Referer');
			}

			$location = elgg_normalize_url($location);

			// return new forward location or false to stop the forward or empty string to exit
			$params = [
				'current_url' => current_page_url(),
				'forward_url' => $location,
			];
			$location = $this->hooks->trigger('forward', $reason, $params, $location);

			if ($location) {
				$response = $this->prepareRedirectResponse($location);
				if ($send) {
					$response->send();
				} else {
					return $response;
				}
			}
			if ($send) {
				exit;
			}
		} else {
			throw new \SecurityException("Redirect could not be issued due to headers already being sent. Halting execution for security. "
			. "Output started in file $file at line $line. Search http://learn.elgg.org/ for more information.");
		}
	}

}
