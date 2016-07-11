<?php

namespace Elgg;

use Elgg\Http\Request;
use Elgg\Http\ResponseBuilder;
use RuntimeException;

/**
 * Delegates requests to controllers based on the registered configuration.
 *
 * Plugin devs should use these wrapper functions:
 *  * elgg_register_page_handler
 *  * elgg_unregister_page_handler
 *
 * @package    Elgg.Core
 * @subpackage Router
 * @since      1.9.0
 * @access private
 */
class Router {

	use Profilable;

	private $handlers = array();
	private $hooks;

	/**
	 * Constructor
	 *
	 * @param PluginHooksService $hooks For customized routing.
	 */
	public function __construct(PluginHooksService $hooks) {
		$this->hooks = $hooks;
	}

	/**
	 * Routes the request to a registered page handler
	 *
	 * This function triggers a plugin hook `'route', $identifier` so that plugins can
	 * modify the routing or handle a request.
	 *
	 * @param Request $request The request to handle.
	 * @return boolean Whether the request was routed successfully.
	 * @access private
	 */
	public function route(Request $request) {
		$segments = $request->getUrlSegments();
		if ($segments) {
			$identifier = array_shift($segments);
		} else {
			$identifier = '';

			// this plugin hook is deprecated. Use elgg_register_page_handler()
			// to register for the '' (empty string) handler.
			// allow plugins to override the front page (return true to indicate
			// that the front page has been served)
			$result = _elgg_services()->hooks->trigger('index', 'system', null, false);
			if ($result === true) {
				elgg_deprecated_notice("The 'index', 'system' plugin has been deprecated. See elgg_front_page_handler()", 1.9);
				exit;
			}
		}

		// return false to stop processing the request (because you handled it)
		// return a new $result array if you want to route the request differently
		$old = array(
			'identifier' => $identifier,
			'handler' => $identifier, // backward compatibility
			'segments' => $segments,
		);

		if ($this->timer) {
			$this->timer->begin(['build page']);
		}

		ob_start();

		$result = $this->hooks->trigger('route', $identifier, $old, $old);
		if ($result === false) {
			$output = ob_get_clean();
			$response = elgg_ok_response($output);
		} else {
			if ($result !== $old) {
				_elgg_services()->logger->warn('Use the route:rewrite hook to modify routes.');
			}

			if ($identifier != $result['identifier']) {
				$identifier = $result['identifier'];
			} else if ($identifier != $result['handler']) {
				$identifier = $result['handler'];
			}

			$segments = $result['segments'];

			$response = false;

			if (isset($this->handlers[$identifier]) && is_callable($this->handlers[$identifier])) {
				$function = $this->handlers[$identifier];
				$response = call_user_func($function, $segments, $identifier);
			}
			
			$output = ob_get_clean();

			if ($response === false) {
				return headers_sent();
			}

			if (!$response instanceof ResponseBuilder) {
				$response = elgg_ok_response($output);
			}
		}

		if (_elgg_services()->responseFactory->getSentResponse()) {
			return true;
		}
		
		_elgg_services()->responseFactory->respond($response);
		return headers_sent();
	}

	/**
	 * Register a function that gets called when the first part of a URL is
	 * equal to the identifier.
	 *
	 * @param string $identifier The page type to handle
	 * @param string $function   Your function name
	 *
	 * @return bool Depending on success
	 */
	public function registerPageHandler($identifier, $function) {
		if (is_callable($function, true)) {
			$this->handlers[$identifier] = $function;
			return true;
		}

		return false;
	}

	/**
	 * Unregister a page handler for an identifier
	 *
	 * @param string $identifier The page type identifier
	 *
	 * @return void
	 */
	public function unregisterPageHandler($identifier) {
		unset($this->handlers[$identifier]);
	}

	/**
	 * Get page handlers as array of identifier => callback
	 * 
	 * @return array
	 */
	public function getPageHandlers() {
		return $this->handlers;
	}

	/**
	 * Filter a request through the route:rewrite hook
	 *
	 * @param Request $request Elgg request
	 *
	 * @return Request
	 * @access private
	 */
	public function allowRewrite(Request $request) {
		$segments = $request->getUrlSegments();
		if ($segments) {
			$identifier = array_shift($segments);
		} else {
			$identifier = '';
		}

		$old = array(
			'identifier' => $identifier,
			'segments' => $segments,
		);
		$new = _elgg_services()->hooks->trigger('route:rewrite', $identifier, $old, $old);
		if ($new === $old) {
			return $request;
		}

		if (!isset($new['identifier']) || !isset($new['segments']) || !is_string($new['identifier']) || !is_array($new['segments'])
		) {
			throw new RuntimeException('rewrite_path handler returned invalid route data.');
		}

		// rewrite request
		$segments = $new['segments'];
		array_unshift($segments, $new['identifier']);
		return $request->setUrlSegments($segments);
	}

}
