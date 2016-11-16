<?php

namespace Elgg\Http;

use Elgg\Ajax\Service as AjaxService;
use Elgg\PluginHooksService;
use ElggEntity;
use InvalidArgumentException;
use InvalidParameterException;
use Symfony\Component\HttpFoundation\RedirectResponse as SymfonyRedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * @since 2.3
 * @access private
 */
class ResponseFactory {

	/**
	 * @var Request
	 */
	private $request;

	/**
	 * @var AjaxService
	 */
	private $ajax;

	/**
	 * @var PluginHooksService
	 */
	private $hooks;

	/**
	 * @var ResponseTransport
	 */
	private $transport;

	/**
	 * @var Response|bool
	 */
	private $response_sent = false;

	/**
	 * @var ResponseHeaderBag
	 */
	private $headers;

	/**
	 * Constructor
	 * 
	 * @param Request            $request   HTTP request
	 * @param PluginHooksService $hooks     Plugin hooks service
	 * @param AjaxService        $ajax      AJAX service
	 * @param ResponseTransport  $transport Response transport
	 */
	public function __construct(Request $request, PluginHooksService $hooks, AjaxService $ajax, ResponseTransport $transport) {
		$this->request = $request;
		$this->hooks = $hooks;
		$this->ajax = $ajax;
		$this->transport = $transport;
		$this->headers = new ResponseHeaderBag();
	}

	/**
	 * Sets headers to apply to all responses being sent
	 *
	 * @param string $name    Header name
	 * @param string $value   Header value
	 * @param bool   $replace Replace existing headers
	 * @return void
	 */
	public function setHeader($name, $value, $replace = true) {
		$this->headers->set($name, $value, $replace);
	}

	/**
	 * Get headers set to apply to all responses
	 *
	 * @param bool $remove_existing Remove existing headers found in headers_list()
	 * @return ResponseHeaderBag
	 */
	public function getHeaders($remove_existing = true) {
		// Add headers that have already been set by underlying views
		// e.g. viewtype page shells set content-type headers
		$headers_list = headers_list();
		foreach ($headers_list as $header) {
			if (stripos($header, 'HTTP/1.1') !== false) {
				continue;
			}

			list($name, $value) = explode(':', $header, 2);
			$this->setHeader($name, ltrim($value), false);
			if ($remove_existing) {
				header_remove($name);
			}
		}

		return $this->headers;
	}

	/**
	 * Creates an HTTP response
	 *
	 * @param string  $content The response content
	 * @param integer $status  The response status code
	 * @param array   $headers An array of response headers
	 * @return Response
	 */
	public function prepareResponse($content = '', $status = 200, array $headers = array()) {
		$header_bag = $this->getHeaders();
		$header_bag->add($headers);
		$response = new Response($content, $status, $header_bag->all());
		$response->prepare($this->request);
		return $response;
	}

	/**
	 * Creates a redirect response
	 *
	 * @param string  $url     URL to redirect to
	 * @param integer $status  The status code (302 by default)
	 * @param array   $headers An array of response headers (Location is always set to the given URL)
	 * @return SymfonyRedirectResponse
	 * @throws InvalidArgumentException
	 */
	public function prepareRedirectResponse($url, $status = 302, array $headers = array()) {
		$header_bag = $this->getHeaders();
		$header_bag->add($headers);
		$response = new SymfonyRedirectResponse($url, $status, $header_bag->all());
		$response->prepare($this->request);
		return $response;
	}

	/**
	 * Send a response
	 *
	 * @param Response $response Response object
	 * @return Response
	 */
	public function send(Response $response) {

		if ($this->response_sent) {
			if ($this->response_sent !== $response) {
				_elgg_services()->logger->error('Unable to send the following response: ' . PHP_EOL
						. (string) $response . PHP_EOL
						. 'because another response has already been sent: ' . PHP_EOL
						. (string) $this->response_sent);
			}
		} else {
			if (!elgg_trigger_before_event('send', 'http_response', $response)) {
				return false;
			}

			if (!$this->transport->send($response)) {
				return false;
			}

			elgg_trigger_after_event('send', 'http_response', $response);
			$this->response_sent = $response;
		}

		return $this->response_sent;
	}

	/**
	 * Returns a response that was sent to the client
	 * @return Response|false
	 */
	public function getSentResponse() {
		return $this->response_sent;
	}

	/**
	 * Send HTTP response
	 *
	 * @param ResponseBuilder $response ResponseBuilder instance
	 *                                  An instance of an ErrorResponse, OkResponse or RedirectResponse
	 * @return Response
	 */
	public function respond(ResponseBuilder $response) {

		$response_type = $this->parseContext();
		$response = $this->hooks->trigger('response', $response_type, $response, $response);
		if (!$response instanceof ResponseBuilder) {
			throw new InvalidParameterException("Handlers for 'response','$response_type' plugin hook must "
			. "return an instanceof " . ResponseBuilder::class);
		}

		if ($response->isNotModified()) {
			return $this->send($this->prepareResponse('', ELGG_HTTP_NOT_MODIFIED));
		}

		$is_xhr = $this->request->isXmlHttpRequest();

		$is_action = false;
		if (0 === strpos($response_type, 'action:')) {
			$is_action = true;
		}

		if ($is_action && $response->getForwardURL() === null) {
			// actions must always set a redirect url
			$response->setForwardURL(REFERRER);
		}

		if ($response->getForwardURL() === REFERRER) {
			$response->setForwardURL($this->request->headers->get('Referer'));
		}

		if ($response->getForwardURL() !== null && !$is_xhr) {
			// non-xhr requests should issue a forward if redirect url is set 
			// unless it's an error, in which case we serve an error page
			if ($this->isAction() || (!$response->isClientError() && !$response->isServerError())) {
				$response->setStatusCode(ELGG_HTTP_FOUND);
			}
		}

		if ($is_xhr && $is_action && !$this->ajax->isAjax2Request()) {
			// xhr actions using legacy ajax API should return 200 with wrapped data
			$response->setStatusCode(ELGG_HTTP_OK);
			$response->setContent($this->wrapLegacyAjaxResponse($response->getContent(), $response->getForwardURL()));
		}

		if ($is_xhr && $is_action) {
			// Actions always respond with JSON on xhr calls
			$headers = $response->getHeaders();
			$headers['Content-Type'] = 'application/json; charset=UTF-8';
			$response->setHeaders($headers);
		}

		$content = $this->stringify($response->getContent());
		$status_code = $response->getStatusCode();
		$headers = $response->getHeaders();

		if ($response->isRedirection()) {
			$redirect_url = $response->getForwardURL();
			return $this->redirect($redirect_url, $status_code);
		}

		if ($this->ajax->isReady() && $response->isSuccessful()) {
			return $this->respondFromContent($content, $status_code, $headers);
		}

		if ($response->isClientError() || $response->isServerError() || $response instanceof ErrorResponse) {
			return $this->respondWithError($content, $status_code, $headers);
		}

		return $this->respondFromContent($content, $status_code, $headers);
	}

	/**
	 * Send error HTTP response
	 *
	 * @param string $error       Error message
	 * @param int    $status_code HTTP status code
	 * @param array  $headers     HTTP headers (will be discarded on AJAX requests)
	 * @return Response
	 * @throws InvalidParameterException
	 */
	public function respondWithError($error, $status_code = ELGG_HTTP_BAD_REQUEST, array $headers = []) {
		if ($this->ajax->isReady()) {
			return $this->send($this->ajax->respondWithError($error, $status_code));
		}

		if ($this->isXhr()) {
			// xhr calls to non-actions (e.g. ajax/view or ajax/form) need to receive proper HTTP status code
			return $this->send($this->prepareResponse($error, $status_code, $headers));
		}

		$forward_url = $this->request->headers->get('Referer');

		if (!$this->isAction()) {
			$params = [
				'current_url' => current_page_url(),
				'forward_url' => $forward_url,
			];
			// For BC, let plugins serve their own error page
			// @see elgg_error_page_handler
			$forward_reason = (string) $status_code;

			$forward_url = $this->hooks->trigger('forward', $forward_reason, $params, $forward_url);

			if ($this->response_sent) {
				// Response was sent from a forward hook
				return $this->response_sent;
			}

			$params['type'] = $forward_reason;
			$error_page = elgg_view_resource('error', $params);
			return $this->send($this->prepareResponse($error_page, $status_code));
		}

		$forward_url = elgg_normalize_url($forward_url);
		return $this->send($this->prepareRedirectResponse($forward_url));
	}

	/**
	 * Send OK response
	 *
	 * @param string $content     Response body
	 * @param int    $status_code HTTP status code
	 * @param array  $headers     HTTP headers (will be discarded for AJAX requets)
	 * @return type
	 */
	public function respondFromContent($content = '', $status_code = ELGG_HTTP_OK, array $headers = []) {

		if ($this->ajax->isReady()) {
			$hook_type = $this->parseContext();
			// $this->ajax->setStatusCode($status_code);
			return $this->send($this->ajax->respondFromOutput($content, $hook_type));
		}

		return $this->send($this->prepareResponse($content, $status_code, $headers));
	}

	/**
	 * Wraps content for compability with legacy Elgg ajax calls
	 *
	 * @param string $content     Response content
	 * @param string $forward_url Forward URL
	 * @return string
	 */
	public function wrapLegacyAjaxResponse($content = '', $forward_url = REFERRER) {

		$content = $this->stringify($content);

		if ($forward_url === REFERRER) {
			$forward_url = $this->request->headers->get('Referer');
		}

		// always pass the full structure to avoid boilerplate JS code.
		$params = [
			'output' => '',
			'status' => 0,
			'system_messages' => [
				'error' => [],
				'success' => []
			],
			'current_url' => current_page_url(),
			'forward_url' => elgg_normalize_url($forward_url),
		];

		$params['output'] = $this->ajax->decodeJson($content);

		// Grab any system messages so we can inject them via ajax too
		$system_messages = _elgg_services()->systemMessages->dumpRegister();

		if (isset($system_messages['success'])) {
			$params['system_messages']['success'] = $system_messages['success'];
		}

		if (isset($system_messages['error'])) {
			$params['system_messages']['error'] = $system_messages['error'];
			$params['status'] = -1;
		}

		$response_type = $this->parseContext();
		list($service, $name) = explode(':', $response_type);
		$context = [
			$service => $name,
		];
		$params = $this->hooks->trigger('output', 'ajax', $context, $params);

		return $this->stringify($params);
	}

	/**
	 * Prepares a redirect response
	 *
	 * @param string $forward_url Redirection URL
	 * @param mixed  $status_code HTTP status code or forward reason
	 * @return SymfonyRedirectResponse
	 * @throws InvalidParameterException
	 */
	public function redirect($forward_url = REFERRER, $status_code = ELGG_HTTP_FOUND) {

		if ($forward_url === REFERRER) {
			$forward_url = $this->request->headers->get('Referer');
		}

		$forward_url = elgg_normalize_url($forward_url);

		// allow plugins to rewrite redirection URL
		$current_page = current_page_url();
		$params = [
			'current_url' => $current_page,
			'forward_url' => $forward_url
		];

		$forward_reason = (string) $status_code;

		$forward_url = $this->hooks->trigger('forward', $forward_reason, $params, $forward_url);

		if ($this->response_sent) {
			// Response was sent from a forward hook
			// Clearing handlers to void infinite loops
			return $this->response_sent;
		}

		if ($forward_url === REFERRER) {
			$forward_url = $this->request->headers->get('Referer');
		}

		if (!is_string($forward_url)) {
			throw new InvalidParameterException("'forward', '$forward_reason' hook must return a valid redirection URL");
		}

		$forward_url = elgg_normalize_url($forward_url);

		switch ($status_code) {
			case 'system':
			case 'csrf':
				$status_code = ELGG_HTTP_OK;
				break;
			case 'admin':
			case 'login':
			case 'member':
			case 'walled_garden':
			default :
				$status_code = (int) $status_code;
				if (!$status_code || $status_code < 100 || $status_code > 599) {
					$status_code = ELGG_HTTP_SEE_OTHER;
				}
				break;
		}

		if ($this->isXhr()) {
			if ($status_code < 100 || ($status_code >= 300 && $status_code <= 399) || $status_code > 599) {
				// We only want to preserve OK and error codes
				// Redirect responses should be converted to OK responses as this is an XHR request
				$status_code = ELGG_HTTP_OK;
			}
			$output = ob_get_clean();
			if (!$this->isAction() && !$this->ajax->isAjax2Request()) {
				// legacy ajax calls are always OK
				// actions are wrapped by ResponseFactory::respond()
				$status_code = ELGG_HTTP_OK;
				$output = $this->wrapLegacyAjaxResponse($output, $forward_url);
			}

			$response = new OkResponse($output, $status_code, $forward_url);
			$headers = $response->getHeaders();
			$headers['Content-Type'] = 'application/json; charset=UTF-8';
			$response->setHeaders($headers);
			return $this->respond($response);
		}

		if ($this->isAction()) {
			// actions should always redirect on non xhr-calls
			if (!is_int($status_code) || $status_code < 300 || $status_code > 399) {
				$status_code = ELGG_HTTP_SEE_OTHER;
			}
		}

		$response = new OkResponse('', $status_code, $forward_url);
		if ($response->isRedirection()) {
			return $this->send($this->prepareRedirectResponse($forward_url, $status_code));
		}
		return $this->respond($response);
	}

	/**
	 * Parses response type to be used as plugin hook type
	 * @return string
	 */
	public function parseContext() {

		$segments = $this->request->getUrlSegments();

		$identifier = array_shift($segments);
		switch ($identifier) {
			case 'ajax' :
				$page = array_shift($segments);
				if ($page === 'view') {
					$view = implode('/', $segments);
					return "view:$view";
				} else if ($page === 'form') {
					$form = implode('/', $segments);
					return "form:$form";
				}
				array_unshift($segments, $page);
				break;

			case 'action' :
				$action = implode('/', $segments);
				return "action:$action";
		}

		array_unshift($segments, $identifier);
		$path = implode('/', $segments);
		return "path:$path";
	}

	/**
	 * Check if the request is an XmlHttpRequest
	 * @return bool
	 */
	public function isXhr() {
		return $this->request->isXmlHttpRequest();
	}

	/**
	 * Check if the requested path is an action
	 * @return bool
	 */
	public function isAction() {
		if (0 === strpos($this->parseContext(), 'action:')) {
			return true;
		}
		return false;
	}

	/**
	 * Normalizes content into serializable data by walking through arrays
	 * and objectifying Elgg entities
	 * 
	 * @param mixed $content Data to normalize
	 * @return mixed
	 */
	public function normalize($content = '') {
		if ($content instanceof ElggEntity) {
			$content = (array) $content->toObject();
		}
		if (is_array($content)) {
			foreach ($content as $key => $value) {
				$content[$key] = $this->normalize($value);
			}
		}
		return $content;
	}

	/**
	 * Stringify/serialize response data
	 *
	 * Casts objects implementing __toString method to strings
	 * Serializes non-scalar values to JSON
	 *
	 * @param mixed $content Content to serialize
	 * @return string
	 */
	public function stringify($content = '') {
		$content = $this->normalize($content);
		if (empty($content) || (is_object($content) && is_callable($content, '__toString'))) {
			return (string) $content;
		}
		if (is_scalar($content)) {
			return $content;
		}
		return json_encode($content, ELGG_JSON_ENCODING);
	}

}
