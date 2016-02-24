<?php
namespace Elgg;

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
	 * @param \Elgg\PluginHooksService $hooks For customized routing.
	 */
	public function __construct(\Elgg\PluginHooksService $hooks) {
		$this->hooks = $hooks;
	}

	/**
	 * Routes the request to a registered page handler
	 *
	 * This function triggers a plugin hook `'route', $identifier` so that plugins can
	 * modify the routing or handle a request.
	 *
	 * @param \Elgg\Http\Request $request The request to handle.
	 * @return boolean Whether the request was routed successfully.
	 * @access private
	 */
	public function route(\Elgg\Http\Request $request) {
		$segments = $request->getUrlSegments();
		if ($segments) {
			$identifier = array_shift($segments);
		} else {
			$identifier = '';
		}

		// return false to stop processing the request (because you handled it)
		// return a new $result array if you want to route the request differently
		$result = array(
			'identifier' => $identifier,
			'handler' => $identifier, // backward compatibility
			'segments' => $segments,
		);

		if ($this->timer) {
			$this->timer->begin(['build page']);
		}

		$result = $this->hooks->trigger('route', $identifier, $result, $result);
		if ($result === false) {
			return true;
		}

		if ($identifier != $result['identifier']) {
			$identifier = $result['identifier'];
		} else if ($identifier != $result['handler']) {
			$identifier = $result['handler'];
		}

		$segments = $result['segments'];

		$handled = false;
		ob_start();

		if (isset($this->handlers[$identifier]) && is_callable($this->handlers[$identifier])) {
			$function = $this->handlers[$identifier];
			$handled = call_user_func($function, $segments, $identifier);
		}

		$output = ob_get_clean();

		$ajax_api = _elgg_services()->ajax;
		if ($ajax_api->isReady()) {
			$path = implode('/', $request->getUrlSegments());
			$ajax_api->respondFromOutput($output, "path:$path");
			return true;
		}

		echo $output;
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
}

