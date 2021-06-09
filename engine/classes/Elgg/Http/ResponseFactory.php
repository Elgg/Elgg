<?php

namespace Elgg\Http;

use Elgg\Ajax\Service as AjaxService;
use Elgg\EventsService;
use Elgg\Exceptions\InvalidParameterException;
use Elgg\PluginHooksService;
use Elgg\Traits\Loggable;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse as SymfonyRedirectResponse;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * @since 2.3
 * @internal
 */
class ResponseFactory {

	use Loggable;

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
	 * @var SymfonyResponse|false
	 */
	private $response_sent = false;

	/**
	 * @var ResponseHeaderBag
	 */
	private $headers;
	
	/**
	 * @var EventsService
	 */
	private $events;

	/**
	 * Constructor
	 *
	 * @param Request            $request   HTTP request
	 * @param PluginHooksService $hooks     Plugin hooks service
	 * @param AjaxService        $ajax      AJAX service
	 * @param ResponseTransport  $transport Response transport
	 * @param EventsService      $events    Events service
	 */
	public function __construct(Request $request, PluginHooksService $hooks, AjaxService $ajax, ResponseTransport $transport, EventsService $events) {
		$this->request = $request;
		$this->hooks = $hooks;
		$this->ajax = $ajax;
		$this->transport = $transport;
		$this->events = $events;
		
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
	 * Set a cookie, but allow plugins to customize it first.
	 *
	 * To customize all cookies, register for the 'init:cookie', 'all' event.
	 *
	 * @param \ElggCookie $cookie The cookie that is being set
	 * @return bool
	 */
	public function setCookie(\ElggCookie $cookie) {
		if (!$this->events->trigger('init:cookie', $cookie->name, $cookie)) {
			return false;
		}

		$symfony_cookie = new Cookie(
			$cookie->name,
			$cookie->value,
			$cookie->expire,
			$cookie->path,
			$cookie->domain,
			$cookie->secure,
			$cookie->httpOnly
		);

		$this->headers->setCookie($symfony_cookie);
		return true;
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
	 * @param mixed   $content The response content
	 * @param integer $status  The response status code
	 * @param array   $headers An array of response headers
	 *
	 * @return SymfonyResponse
	 */
	public function prepareResponse($content = '', $status = 200, array $headers = []) {
		$header_bag = $this->getHeaders();
		$header_bag->add($headers);
		
		$response = new SymfonyResponse($content, $status, $header_bag->all());
		
		$response->prepare($this->request);
		
		return $response;
	}

	/**
	 * Creates a redirect response
	 *
	 * @param string  $url     URL to redirect to
	 * @param integer $status  The status code (302 by default)
	 * @param array   $headers An array of response headers (Location is always set to the given URL)
	 *
	 * @return SymfonyRedirectResponse
	 */
	public function prepareRedirectResponse($url, $status = 302, array $headers = []) {
		$header_bag = $this->getHeaders();
		$header_bag->add($headers);
		
		$response = new SymfonyRedirectResponse($url, $status, $header_bag->all());
		
		$response->prepare($this->request);
		
		return $response;
	}
	
	/**
	 * Creates an JSON response
	 *
	 * @param mixed   $content The response content
	 * @param integer $status  The response status code
	 * @param array   $headers An array of response headers
	 *
	 * @return JsonResponse
	 */
	public function prepareJsonResponse($content = '', $status = 200, array $headers = []) {
		$header_bag = $this->getHeaders();
		$header_bag->add($headers);
		
		/**
		 * Removing Content-Type header because in some cases content-type headers were already set
		 * This is a problem when serving a cachable view (for example a .css) in ajax/view
		 *
		 * @see https://github.com/Elgg/Elgg/issues/9794
		 */
		$header_bag->remove('Content-Type');
		
		$response = new JsonResponse($content, $status, $header_bag->all());
		
		$response->prepare($this->request);
		
		return $response;
	}

	/**
	 * Send a response
	 *
	 * @param SymfonyResponse $response Response object
	 *
	 * @return SymfonyResponse|false
	 */
	public function send(SymfonyResponse $response) {

		if ($this->response_sent) {
			if ($this->response_sent !== $response) {
				$this->getLogger()->error('Unable to send the following response: ' . PHP_EOL
						. (string) $response . PHP_EOL
						. 'because another response has already been sent: ' . PHP_EOL
						. (string) $this->response_sent);
			}
		} else {
			if (!$this->events->triggerBefore('send', 'http_response', $response)) {
				return false;
			}

			$request = $this->request;
			$method = $request->getRealMethod() ? : 'GET';
			$path = $request->getElggPath();

			$this->getLogger()->notice("Responding to {$method} {$path}");
			if (!$this->transport->send($response)) {
				return false;
			}

			$this->events->triggerAfter('send', 'http_response', $response);
			$this->response_sent = $response;
			
			$this->closeSession();
		}

		return $this->response_sent;
	}

	/**
	 * Returns a response that was sent to the client
	 *
	 * @return SymfonyResponse|false
	 */
	public function getSentResponse() {
		return $this->response_sent;
	}

	/**
	 * Send HTTP response
	 *
	 * @param ResponseBuilder $response ResponseBuilder instance
	 *                                  An instance of an ErrorResponse, OkResponse or RedirectResponse
	 * @return false|SymfonyResponse
	 * @throws InvalidParameterException
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

		// Prevent content type sniffing by the browser
		$headers = $response->getHeaders();
		$headers['X-Content-Type-Options'] = 'nosniff';
		$response->setHeaders($headers);
		
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

		if ($is_xhr && ($is_action || $this->ajax->isAjax2Request())) {
			// Actions and calls from elgg/Ajax always respond with JSON on xhr calls
			$headers = $response->getHeaders();
			$headers['Content-Type'] = 'application/json; charset=UTF-8';
			$response->setHeaders($headers);

			if ($response->isOk()) {
				$response->setContent($this->wrapAjaxResponse($response->getContent(), $response->getForwardURL()));
			}
		}

		if ($response->isRedirection()) {
			$redirect_url = $response->getForwardURL();
			return $this->redirect($redirect_url, $response->getStatusCode());
		}

		if ($this->ajax->isReady() && $response->isSuccessful()) {
			return $this->respondFromContent($response);
		}

		if ($response->isClientError() || $response->isServerError() || $response instanceof ErrorResponse) {
			return $this->respondWithError($response);
		}

		return $this->respondFromContent($response);
	}

	/**
	 * Send error HTTP response
	 *
	 * @param ResponseBuilder $response ResponseBuilder instance
	 *                                  An instance of an ErrorResponse, OkResponse or RedirectResponse
	 *
	 * @return false|SymfonyResponse
	 */
	public function respondWithError(ResponseBuilder $response) {
		$error = $this->stringify($response->getContent());
		$status_code = $response->getStatusCode();

		if ($this->ajax->isReady()) {
			return $this->send($this->ajax->respondWithError($error, $status_code));
		}

		if ($this->isXhr()) {
			// xhr calls to non-actions (e.g. ajax/view or ajax/form) need to receive proper HTTP status code
			return $this->send($this->prepareResponse($error, $status_code, $response->getHeaders()));
		}

		$forward_url = $this->getSiteRefererUrl();

		if (!$this->isAction()) {
			$params = [
				'current_url' => current_page_url(),
				'forward_url' => $forward_url,
			];
			// For BC, let plugins serve their own error page
			// @todo can this hook be dropped
			$forward_reason = (string) $status_code;

			$this->hooks->trigger('forward', $forward_reason, $params, $forward_url);

			if ($this->response_sent) {
				// Response was sent from a forward hook
				return $this->response_sent;
			}

			if (elgg_view_exists('resources/error')) {
				$params['type'] = $forward_reason;
				$params['exception'] = $response->getException();
				if (!elgg_is_empty($error)) {
					$params['params']['error'] = $error;
				}
				$error_page = elgg_view_resource('error', $params);
			} else {
				$error_page = $error;
			}

			return $this->send($this->prepareResponse($error_page, $status_code));
		}

		$forward_url = $this->makeSecureForwardUrl($forward_url);
		return $this->send($this->prepareRedirectResponse($forward_url));
	}

	/**
	 * Send OK response
	 *
	 * @param ResponseBuilder $response ResponseBuilder instance
	 *                                  An instance of an ErrorResponse, OkResponse or RedirectResponse
	 *
	 * @return SymfonyResponse|false
	 */
	public function respondFromContent(ResponseBuilder $response) {
		$content = $this->stringify($response->getContent());
		
		if ($this->ajax->isReady()) {
			$hook_type = $this->parseContext();
			return $this->send($this->ajax->respondFromOutput($content, $hook_type));
		}

		return $this->send($this->prepareResponse($content, $response->getStatusCode(), $response->getHeaders()));
	}

	/**
	 * Wraps response content in an Ajax2 compatible format
	 *
	 * @param string $content     Response content
	 * @param string $forward_url Forward URL
	 * @return string
	 */
	public function wrapAjaxResponse($content = '', $forward_url = null) {

		$content = $this->stringify($content);

		if ($forward_url === REFERRER) {
			$forward_url = $this->getSiteRefererUrl();
		}

		$params = [
			'value' => '',
			'current_url' => current_page_url(),
			'forward_url' => elgg_normalize_url($forward_url),
		];

		$params['value'] = $this->ajax->decodeJson($content);

		return $this->stringify($params);
	}

	/**
	 * Prepares a redirect response
	 *
	 * @param string $forward_url Redirection URL
	 * @param mixed  $status_code HTTP status code or forward reason
	 * @return false|SymfonyResponse
	 * @throws InvalidParameterException
	 */
	public function redirect($forward_url = REFERRER, $status_code = ELGG_HTTP_FOUND) {
		$location = $forward_url;
		
		if ($forward_url === REFERRER) {
			$forward_url = $this->getSiteRefererUrl();
		}

		$forward_url = $this->makeSecureForwardUrl($forward_url);

		// allow plugins to rewrite redirection URL
		$params = [
			'current_url' => current_page_url(),
			'forward_url' => $forward_url,
			'location' => $location,
		];

		$forward_reason = (string) $status_code;

		$forward_url = $this->hooks->trigger('forward', $forward_reason, $params, $forward_url);
		
		if ($this->response_sent) {
			// Response was sent from a forward hook
			// Clearing handlers to void infinite loops
			return $this->response_sent;
		}

		if ($forward_url === REFERRER) {
			$forward_url = $this->getSiteRefererUrl();
		}

		if (!is_string($forward_url)) {
			throw new InvalidParameterException("'forward', '$forward_reason' hook must return a valid redirection URL");
		}

		$forward_url = $this->makeSecureForwardUrl($forward_url);

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

			$response = new RedirectResponse($forward_url, $status_code);
			$response->setContent($output);
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

		$response = new RedirectResponse($forward_url, $status_code);
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
		if ($content instanceof \ElggEntity) {
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
		
		if (is_object($content) && is_callable([$content, '__toString'])) {
			return (string) $content;
		}
		
		if (is_scalar($content)) {
			return (string) $content;
		}
		
		if (empty($content)) {
			return '';
		}
		
		return json_encode($content, ELGG_JSON_ENCODING);
	}

	/**
	 * Replaces response transport
	 *
	 * @param ResponseTransport $transport Transport interface
	 * @return void
	 */
	public function setTransport(ResponseTransport $transport) {
		$this->transport = $transport;
	}
	
	/**
	 * Ensures the referer header is a site url
	 *
	 * @return string
	 */
	protected function getSiteRefererUrl() {
		$unsafe_url = $this->request->headers->get('Referer');
		$safe_url = elgg_normalize_site_url($unsafe_url);
		if ($safe_url !== false) {
			return $safe_url;
		}
		
		return '';
	}
	
	/**
	 * Ensure the url has a valid protocol for browser use
	 *
	 * @param string $url url the secure
	 *
	 * @return string
	 */
	protected function makeSecureForwardUrl($url) {
		$url = elgg_normalize_url($url);
		if (!preg_match('/^(http|https|ftp|sftp|ftps):\/\//', $url)) {
			return elgg_get_site_url();
		}
		
		return $url;
	}
	
	/**
	 * Closes the session
	 *
	 * Force closing the session so session is saved to the database before headers are sent
	 * preventing race conditions with session data
	 *
	 * @see https://github.com/Elgg/Elgg/issues/12348
	 *
	 * @return void
	 */
	protected function closeSession() {
		$session = elgg_get_session();
		if ($session->isStarted()) {
			$session->save();
		}
	}
}
