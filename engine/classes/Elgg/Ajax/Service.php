<?php

namespace Elgg\Ajax;

use Elgg\Amd\Config;
use Elgg\EventsService;
use Elgg\Exceptions\RuntimeException;
use Elgg\Http\Request;
use Elgg\Services\AjaxResponse;
use Elgg\SystemMessagesService;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Models the Ajax API service
 *
 * @since 1.12.0
 * @internal
 */
class Service {

	/**
	 * @var EventsService
	 */
	private $events;

	/**
	 * @var SystemMessagesService
	 */
	private $msgs;

	/**
	 * @var Request
	 */
	private $request;

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
	 * @param EventsService         $events    Events service
	 * @param SystemMessagesService $msgs      System messages service
	 * @param Request               $request   Http Request
	 * @param Config                $amdConfig AMD config
	 */
	public function __construct(EventsService $events, SystemMessagesService $msgs, Request $request, Config $amdConfig) {
		$this->events = $events;
		$this->msgs = $msgs;
		$this->request = $request;
		$this->amd_config = $amdConfig;

		$message_filter = [$this, 'prepareResponse'];
		$this->events->registerHandler(AjaxResponse::RESPONSE_EVENT, 'all', $message_filter, 999);
	}

	/**
	 * Did the request come from the elgg/Ajax module?
	 *
	 * @return bool
	 */
	public function isAjax2Request(): bool {
		$version = $this->request->headers->get('X-Elgg-Ajax-API');
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
	public function isReady(): bool {
		return !$this->response_sent && $this->isAjax2Request();
	}

	/**
	 * Attempt to JSON decode the given string
	 *
	 * @param mixed $string Output string
	 *
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
	 * @param string $event_type The event type. If given, the response will be filtered by event
	 * @param bool   $try_decode Try to convert a JSON string back to an abject
	 *
	 * @return JsonResponse|false
	 */
	public function respondFromOutput($output, string $event_type = '', bool $try_decode = true) {
		if ($try_decode) {
			$output = $this->decodeJson($output);
		}

		$api_response = new Response();
		if (is_object($output) && isset($output->value)) {
			$api_response->setData($output);
		} elseif (is_array($output) && isset($output['value'])) {
			$api_response->setData((object) $output);
		} else {
			$api_response->setData((object) ['value' => $output]);
		}
		
		$api_response = $this->filterApiResponse($api_response, $event_type);
		$response = $this->buildHttpResponse($api_response);

		$this->response_sent = true;
		return _elgg_services()->responseFactory->send($response);
	}

	/**
	 * Send a JSON HTTP response based on the given API response
	 *
	 * @param AjaxResponse $api_response API response
	 * @param string       $event_type   The event type. If given, the response will be filtered by event
	 *
	 * @return JsonResponse|false
	 */
	public function respondFromApiResponse(AjaxResponse $api_response, string $event_type = '') {
		$api_response = $this->filterApiResponse($api_response, $event_type);
		$response = $this->buildHttpResponse($api_response);

		$this->response_sent = true;
		return _elgg_services()->responseFactory->send($response);
	}

	/**
	 * Send a JSON HTTP 400 response
	 *
	 * @param string $msg    The error message (not displayed to the user)
	 * @param int    $status The HTTP status code
	 *
	 * @return JsonResponse|false
	 */
	public function respondWithError(string $msg = '', int $status = 400) {
		$response = new JsonResponse(['error' => $msg], $status);
		
		// clear already set system messages as we respond directly with an error as message body
		$this->msgs->dumpRegister();

		$this->response_sent = true;
		return _elgg_services()->responseFactory->send($response);
	}

	/**
	 * Filter an AjaxResponse through a event
	 *
	 * @param AjaxResponse $api_response The API Response
	 * @param string       $event_type   The event type. If given, the response will be filtered by event
	 *
	 * @return AjaxResponse
	 * @throws RuntimeException
	 */
	private function filterApiResponse(AjaxResponse $api_response, string $event_type = ''): AjaxResponse {
		$api_response->setTtl($this->request->getParam('elgg_response_ttl', 0, false));

		if ($event_type) {
			$event_name = AjaxResponse::RESPONSE_EVENT;
			$api_response = $this->events->triggerResults($event_name, $event_type, [], $api_response);
			if (!$api_response instanceof AjaxResponse) {
				throw new RuntimeException("The value returned by event [{$event_name}, {$event_type}] was not an ApiResponse");
			}
		}

		return $api_response;
	}

	/**
	 * Build a JsonResponse based on an API response object
	 *
	 * @param AjaxResponse $api_response The API Response
	 *
	 * @return JsonResponse
	 * @throws RuntimeException
	 */
	private function buildHttpResponse(AjaxResponse $api_response): JsonResponse {
		if ($api_response->isCancelled()) {
			return new JsonResponse(['error' => 'The response was cancelled'], 400);
		}

		$response = _elgg_services()->responseFactory->prepareJsonResponse($api_response->getData());

		$ttl = $api_response->getTtl();
		if ($ttl > 0) {
			// JsonRequest sets a default Cache-Control header we don't want
			$response->headers->remove('Cache-Control');

			$response->setClientTtl($ttl);

			// if we don't set Expires, Apache will add a far-off max-age and Expires for us.
			$response->headers->set('Expires', gmdate('D, d M Y H:i:s \G\M\T', time() + $ttl));
		}

		return $response;
	}

	/**
	 * Prepare the response with additional metadata, like system messages and required AMD modules
	 *
	 * @param \Elgg\Event $event "ajax_response", "all"
	 *
	 * @return AjaxResponse
	 * @internal
	 */
	public function prepareResponse(\Elgg\Event $event) {
		$response = $event->getValue();
		if (!$response instanceof AjaxResponse) {
			return;
		}

		if ($this->request->getParam('elgg_fetch_messages', true)) {
			$messages = $this->msgs->dumpRegister();
			foreach ($messages as $type => $msgs) {
				$messages[$type] = array_map(function($value) {
					return (string) $value;
				}, $msgs);
			}
			
			$response->getData()->_elgg_msgs = (object) $messages;
		}

		if ($this->request->getParam('elgg_fetch_deps', true)) {
			$response->getData()->_elgg_deps = (array) $this->amd_config->getDependencies();
		}

		return $response;
	}

	/**
	 * Register a view to be available for ajax calls
	 *
	 * @param string $view The view name
	 *
	 * @return void
	 */
	public function registerView(string $view): void {
		$this->allowed_views[$view] = true;
	}

	/**
	 * Unregister a view for ajax calls
	 *
	 * @param string $view The view name
	 *
	 * @return void
	 */
	public function unregisterView(string $view): void {
		unset($this->allowed_views[$view]);
	}

	/**
	 * Returns an array of views allowed for ajax calls
	 *
	 * @return string[]
	 */
	public function getViews(): array {
		return array_keys($this->allowed_views);
	}
}
