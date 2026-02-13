<?php

namespace Elgg\Ajax;

use Elgg\Assets\ExternalFiles;
use Elgg\EventsService;
use Elgg\Exceptions\InvalidArgumentException;
use Elgg\Exceptions\RuntimeException;
use Elgg\Http\Request;
use Elgg\Javascript\ESMService;
use Elgg\SystemMessagesService;
use PHPUnit\Util\Json;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Models the Ajax API service
 *
 * @since 1.12.0
 * @internal
 */
class Service {

	protected bool $response_sent = false;

	protected array $allowed_views = [];

	/**
	 * Constructor
	 *
	 * @param EventsService         $events        Events service
	 * @param SystemMessagesService $msgs          System messages service
	 * @param Request               $request       Http Request
	 * @param ESMService            $esm           ESM service
	 * @param ExternalFiles         $externalFiles External files service
	 */
	public function __construct(
		protected EventsService $events,
		protected SystemMessagesService $msgs,
		protected Request $request,
		protected ESMService $esm,
		protected ExternalFiles $externalFiles
	) {
	}

	/**
	 * Did the request come from the elgg/Ajax module?
	 *
	 * @return bool
	 */
	public function isAjax2Request(): bool {
		return $this->request->headers->get('X-Elgg-Ajax-API') === '2';
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
	public function respondFromOutput(mixed $output, string $event_type = '', bool $try_decode = true): JsonResponse|false {
		if ($try_decode) {
			$output = $this->decodeJson($output);
		}
		
		if (is_object($output) && isset($output->value)) {
			$data = $output;
		} elseif (is_array($output) && isset($output['value'])) {
			$data = (object) $output;
		} else {
			$data = (object) ['value' => $output];
		}

		if ($event_type) {
			$data = (object) $this->events->triggerResults('ajax_results', $event_type, [], $data);
		}

		if (!property_exists($data, 'value')) {
			throw new InvalidArgumentException('$data must have a property "value"');
		}

		if ($this->request->getParam('elgg_fetch_messages', true)) {
			$messages = $this->msgs->dumpRegister();
			foreach ($messages as $type => $msgs) {
				$messages[$type] = array_map(function($value) {
					return (string) $value;
				}, $msgs);
			}

			$data->_elgg_msgs = (object) $messages;
		}

		if ($this->request->getParam('elgg_fetch_deps', true)) {
			$deps = [
				'js' => $this->esm->getImports(),
				'css' => [],
			];

			foreach ($this->externalFiles->getLoadedResources('css', 'head') as $name => $resource) {
				if ($name === 'elgg') {
					// prevent loading of elgg.css in admin context
					continue;
				}

				$deps['css'][] = [
					'name' => $name,
					'href' => $resource->url,
					'integrity' => $resource->integrity,
				];
			}

			$data->_elgg_deps = $deps;
		}
		
		$response = _elgg_services()->responseFactory->prepareJsonResponse($data);
		$response->setTtl((int) $this->request->getParam('elgg_response_ttl', 0, false));

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
	public function respondWithError(string $msg = '', int $status = 400): JsonResponse|false {
		$response = new JsonResponse(['error' => $msg], $status);
		
		// clear already set system messages as we respond directly with an error as message body
		$this->msgs->dumpRegister();

		$this->response_sent = true;
		return _elgg_services()->responseFactory->send($response);
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
