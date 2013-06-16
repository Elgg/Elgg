<?php

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
class Elgg_Router {
	private $handlers = array();
	private $hooks;

	/**
	 * Constructor
	 *
	 * @param Elgg_PluginHooksService $hooks For customized routing.
	 */
	public function __construct(Elgg_PluginHooksService $hooks) {
		$this->hooks = $hooks;
	}

	/**
	 * Routes the request to a registered page handler
	 *
	 * This function triggers a plugin hook `'route', $identifier` so that plugins can
	 * modify the routing or handle a request.
	 *
	 * @param Elgg_Request $request The request to handle.
	 * @return boolean Whether the request was routed successfully.
	 * @access private
	 */
	public function route(Elgg_Request $request) {
		$identifier = (string)$request->query['handler'];
		elgg_set_context($identifier);

		$segments = $this->getUrlSegments($request);

		// return false to stop processing the request (because you handled it)
		// return a new $result array if you want to route the request differently
		$result = array(
			'identifier' => $identifier,
			'handler' => $identifier, // backward compatibility
			'segments' => $segments,
		);
		$result = $this->hooks->trigger('route', $identifier, null, $result);
		if ($result === false) {
			return true;
		}

		$identifier = $result['identifier'];
		$segments = $result['segments'];

		$handled = false;
		if (isset($this->handlers[$identifier]) && is_callable($this->handlers[$identifier])) {
			$function = $this->handlers[$identifier];
			$handled = call_user_func($function, $segments, $identifier);
		}

		return $handled || headers_sent();
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
	 * Get URL segments in an array
	 *
	 * @param Elgg_Request $request The request being routed
	 * @return array
	 */
	protected function getUrlSegments(Elgg_Request $request) {
		$page = $request->query['page'];
		if (!$page) {
			return array();
		}
		$segments = explode('/', $page);
		// remove empty array element when page url ends in a / (see #1480)
		if ($segments[count($segments) - 1] === '') {
			array_pop($segments);
		}

		return $segments;
	}
}
