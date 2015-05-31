<?php
namespace Elgg\AjaxApi;

use Elgg\Di\DiContainer;
use Elgg\Http\Input;
use Elgg\PluginHooksService;
use Elgg\SystemMessagesService;
use Elgg\Services\AjaxApi;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Models the Ajax API service
 *
 * @since 2.0.0
 * @access private
 * @internal
 */
class Service implements AjaxApi {

	/**
	 * @var callable[]
	 */
	private $handlers;

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
	 * Constructor
	 *
	 * @param PluginHooksService    $hooks Hooks service
	 * @param SystemMessagesService $msgs  System messages service
	 * @param Input                 $input Input service
	 */
	public function __construct(PluginHooksService $hooks, SystemMessagesService $msgs, Input $input) {
		$this->hooks = $hooks;
		$this->msgs = $msgs;
		$this->input = $input;
	}

	/**
	 * {@inheritdoc}
	 */
	public function register($endpoint, $class_name) {
		if (!preg_match(DiContainer::CLASS_NAME_PATTERN_53, $class_name)) {
			throw new \InvalidArgumentException("'$class_name' is not a valid class name");
		}
		$this->handlers[$endpoint] = $class_name;
	}

	/**
	 * Handle a request
	 *
	 * @param string $endpoint Endpoint name
	 *
	 * @return Response
	 * @access private
	 * @throws \RuntimeException
	 */
	public function handle($endpoint) {
		$get_error = function ($code, $msg = '') {
			return new JsonResponse(['error' => $msg], $code);
		};

		if (empty($this->handlers[$endpoint])) {
			return $get_error(404, "Unregistered endpoint '$endpoint'");
		}

		$class_name = $this->handlers[$endpoint];
		if (!class_exists($class_name)) {
			throw new \RuntimeException("Class $class_name cannot be found");
		}

		// TODO move into DI system
		$handler = new $class_name;
		if (!is_callable($handler)) {
			throw new \RuntimeException("Class $class_name is not invokable");
		}

		$api_response = new ApiResponse();
		$api_response->setTtl($this->input->get('response_ttl', 0, false));

		$api_response = call_user_func($handler, $api_response, elgg());
		if (!$api_response instanceof \Elgg\Services\AjaxApi\ApiResponse) {
			throw new \RuntimeException("$class_name::__invoke did not return an ApiResponse");
		}

		$hook = AjaxApi::RESPONSE_HOOK;
		$api_response = $this->hooks->trigger($hook, $endpoint, null, $api_response);
		if (!$api_response instanceof \Elgg\Services\AjaxApi\ApiResponse) {
			throw new \RuntimeException("The value returned by hook [$hook, $endpoint] was not an ApiResponse");
		}

		if ($api_response->isCancelled()) {
			return $get_error(400, "The response was cancelled");
		}

		$response = new JsonResponse([
			'msgs' => $this->msgs->dumpRegister(),
			'data' => $api_response->getData(),
		]);

		$ttl = $api_response->getTtl();
		if ($ttl > 0) {
			// Required to remove headers set by PHP session
			header_remove('Expires');
			header_remove('Pragma');
			header_remove('Cache-Control');

			// JsonRequest sets a default Cache-Control header we don't want
			$response->headers->remove('Cache-Control');

			$response->setClientTtl($ttl);

			// if we don't set Expires, Apache will add a far-off max-age and Expires for us.
			$response->headers->set('Expires', gmdate('D, d M Y H:i:s \G\M\T', time() + $ttl));
		}
		return $response;
	}
}
