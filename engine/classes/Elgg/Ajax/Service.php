<?php

namespace Elgg\Ajax;

use Elgg\Amd\Config;
use Elgg\Http\Input;
use Elgg\PluginHooksService;
use Elgg\Services\AjaxResponse;
use Elgg\SystemMessagesService;
use RuntimeException;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Models the Ajax API service
 *
 * @since 1.12.0
 * @access private
 * @internal
 */
class Service {

	/**
	 * @var PluginHooksService
	 */
	private $hooks;

	/**
	 * @var SystemMessagesService
	 */
	private $msgs;

	/**
	 * @var Input
	 */
	private $input;

	/**
	 * @var Config
	 */
	private $amd_config;

	/**
	 * @var bool
	 */
	private $response_sent = false;

	/**
	 * @var array
	 */
	private $allowed_views = [];

	/**
	 * Constructor
	 *
	 * @param PluginHooksService    $hooks     Hooks service
	 * @param SystemMessagesService $msgs      System messages service
	 * @param Input                 $input     Input service
	 * @param Config                $amdConfig AMD config
	 */
	public function __construct(PluginHooksService $hooks, SystemMessagesService $msgs, Input $input, Config $amdConfig) {
		$this->hooks = $hooks;
		$this->msgs = $msgs;
		$this->input = $input;
		$this->amd_config = $amdConfig;

		if ($this->input->get('elgg_fetch_messages', true)) {
			$message_filter = [$this, 'appendMessages'];
			$this->hooks->registerHandler(AjaxResponse::RESPONSE_HOOK, 'all', $message_filter, 999);
		}

		if ($this->input->get('elgg_fetch_deps', true)) {
			$deps_filter = [$this, 'appendDeps'];
			$this->hooks->registerHandler(AjaxResponse::RESPONSE_HOOK, 'all', $deps_filter, 999);
		}
	}

	/**
	 * Did the request come from the elgg/Ajax module?
	 *
	 * @return bool
	 */
	public function isAjax2Request() {
		$version = _elgg_services()->request->headers->get('X-Elgg-Ajax-API');
		return ($version === '2');
	}

	/**
	 * Is the service ready to respond to the request?
	 *
	 * Some code paths involve multiple layers of handling (e.g. router calls actions/ajax views) so
	 * we must check whether the response has already been sent to avoid sending it twice. We
	 * can't use headers_sent() because Router needs to use output buffering.
	 *
	 * @return bool
	 */
	public function isReady() {
		return !$this->response_sent && $this->isAjax2Request();
	}

	/**
	 * Attempt to JSON decode the given string
	 *
	 * @param mixed $string Output string
	 * @return mixed
	 */
	public function decodeJson($string) {
		if (!is_string($string)) {
			return $string;
		}
		$object = json_decode($string);
		return ($object === null) ? $string : $object;
	}

	/**
	 * Send a JSON HTTP response with the given output
	 *
	 * @param mixed  $output     Output from a page/action handler
	 * @param string $hook_type  The hook type. If given, the response will be filtered by hook
	 * @param bool   $try_decode Try to convert a JSON string back to an abject
	 * @return JsonResponse
	 */
	public function respondFromOutput($output, $hook_type = '', $try_decode = true) {
		if ($try_decode) {
			$output = $this->decodeJson($output);
		}

		$api_response = new Response();
		$api_response->setData((object)[
					'value' => $output,
		]);
		$api_response = $this->filterApiResponse($api_response, $hook_type);
		$response = $this->buildHttpResponse($api_response);

		$this->response_sent = true;
		return _elgg_services()->responseFactory->send($response);
	}

	/**
	 * Send a JSON HTTP response based on the given API response
	 *
	 * @param AjaxResponse $api_response API response
	 * @param string       $hook_type    The hook type. If given, the response will be filtered by hook
	 * @return JsonResponse
	 */
	public function respondFromApiResponse(AjaxResponse $api_response, $hook_type = '') {
		$api_response = $this->filterApiResponse($api_response, $hook_type);
		$response = $this->buildHttpResponse($api_response);

		$this->response_sent = true;
		return _elgg_services()->responseFactory->send($response);
	}

	/**
	 * Send a JSON HTTP 400 response
	 *
	 * @param string $msg    The error message (not displayed to the user)
	 * @param int    $status The HTTP status code
	 * @return JsonResponse
	 */
	public function respondWithError($msg = '', $status = 400) {
		$response = new JsonResponse(['error' => $msg], $status);

		$this->response_sent = true;
		return _elgg_services()->responseFactory->send($response);
	}

	/**
	 * Filter an AjaxResponse through a plugin hook
	 *
	 * @param AjaxResponse $api_response The API Response
	 * @param string       $hook_type    The hook type. If given, the response will be filtered by hook
	 *
	 * @return AjaxResponse
	 */
	private function filterApiResponse(AjaxResponse $api_response, $hook_type = '') {
		$api_response->setTtl($this->input->get('elgg_response_ttl', 0, false));

		if ($hook_type) {
			$hook = AjaxResponse::RESPONSE_HOOK;
			$api_response = $this->hooks->trigger($hook, $hook_type, null, $api_response);
			if (!$api_response instanceof AjaxResponse) {
				throw new RuntimeException("The value returned by hook [$hook, $hook_type] was not an ApiResponse");
			}
		}

		return $api_response;
	}

	/**
	 * Build a JsonResponse based on an API response object
	 *
	 * @param AjaxResponse $api_response           The API Response
	 * @param bool         $allow_removing_headers Alter PHP's global headers to allow caching
	 *
	 * @return JsonResponse
	 * @throws RuntimeException
	 */
	private function buildHttpResponse(AjaxResponse $api_response, $allow_removing_headers = null) {
		if ($api_response->isCancelled()) {
			return new JsonResponse(['error' => "The response was cancelled"], 400);
		}

		$response = new JsonResponse($api_response->getData());

		$ttl = $api_response->getTtl();
		if ($ttl > 0) {
			// Required to remove headers set by PHP session
			if (!isset($allow_removing_headers)) {
				$allow_removing_headers = !elgg()->isTestingApplication();
			}

			if ($allow_removing_headers) {
				header_remove('Expires');
				header_remove('Pragma');
				header_remove('Cache-Control');
			}

			// JsonRequest sets a default Cache-Control header we don't want
			$response->headers->remove('Cache-Control');

			$response->setClientTtl($ttl);

			// if we don't set Expires, Apache will add a far-off max-age and Expires for us.
			$response->headers->set('Expires', gmdate('D, d M Y H:i:s \G\M\T', time() + $ttl));
		}

		return $response;
	}

	/**
	 * Send system messages back with the response
	 *
	 * @param string       $hook     "ajax_response"
	 * @param string       $type     "all"
	 * @param AjaxResponse $response Ajax response
	 * @param array        $params   Hook params
	 *
	 * @return AjaxResponse
	 * @access private
	 * @internal
	 */
	public function appendMessages($hook, $type, $response, $params) {
		if (!$response instanceof AjaxResponse) {
			return;
		}
		$response->getData()->_elgg_msgs = (object)$this->msgs->dumpRegister();
		return $response;
	}

	/**
	 * Send required AMD modules list back with the response
	 *
	 * @param string       $hook     "ajax_response"
	 * @param string       $type     "all"
	 * @param AjaxResponse $response Ajax response
	 * @param array        $params   Hook params
	 *
	 * @return AjaxResponse
	 * @access private
	 * @internal
	 */
	public function appendDeps($hook, $type, $response, $params) {
		if (!$response instanceof AjaxResponse) {
			return;
		}
		$response->getData()->_elgg_deps = (array) $this->amd_config->getDependencies();
		return $response;
	}

	/**
	 * Register a view to be available for ajax calls
	 *
	 * @param string $view The view name
	 * @return void
	 */
	public function registerView($view) {
		$this->allowed_views[$view] = true;
	}

	/**
	 * Unregister a view for ajax calls
	 *
	 * @param string $view The view name
	 * @return void
	 */
	public function unregisterView($view) {
		unset($this->allowed_views[$view]);
	}

	/**
	 * Returns an array of views allowed for ajax calls
	 * @return string[]
	 */
	public function getViews() {
		return array_keys($this->allowed_views);
	}
	
}