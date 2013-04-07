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
	private $pagehandlers = array();
	private $hooks;
	
	/**
	 * Constructor
	 * 
	 * @param ElggPluginHookService $hooks For highly customized routing.
	 */
	public function __construct(ElggPluginHookService $hooks) {
		$this->hooks = $hooks;
	}
		
	/**
	 * Routes the request to a registered page handler
	 *
	 * This function triggers a plugin hook `'route', $handler` so that plugins can
	 * modify the routing or handle a request.
	 *
	 * @param string $handler The name of the handler type (eg 'blog')
	 * @param array  $page    The parameters to the page, as an array (exploded by '/' slashes)
	 *
	 * @return bool
	 * @access private
	 * 
	 * @param Elgg_Request $request The request to handle.
	 * @return boolean Whether the request was routed successfully.
	 */
	public function route(Elgg_Request $request) {
		$handler = $request->getInput('handler');
		$page = $request->getInput('page');
		
		elgg_set_context($handler);
		
		$segments = explode('/', $page);
		// remove empty array element when page url ends in a / (see #1480)
		if ($segments[count($segments) - 1] === '') {
			array_pop($segments);
		}
	
		// return false to stop processing the request (because you handled it)
		// return a new $result array if you want to route the request differently
		$result = array(
			'handler' => $handler,
			'segments' => $segments,
		);
		$result = $this->hooks->trigger('route', $handler, null, $result);
		if ($result === false) {
			return true;
		}
		
		$handler = $result['handler'];
		$segments = $result['segments'];
	
		$handled = false;
		if (!empty($handler)
				&& isset($this->pagehandlers[$handler])
				&& is_callable($this->pagehandlers[$handler])) {
			$function = $this->pagehandlers[$handler];
			$handled = call_user_func($function, $segments, $handler);
		}
		
		return $handled || headers_sent();
	}
	

	/**
	 * Register a function that gets called when the first part of a URL is
	 * equal to the handler.
	 * 
	 * @param string $handler  The page type to handle
	 * @param string $function Your function name
	 *
	 * @return bool Depending on success
	 */
	public function registerPageHandler($handler, $function) {
		if (is_callable($function, true)) {
			$this->pagehandlers[$handler] = $function;
			return true;
		}
	
		return false;
	}
	
	/**
	 * Any previously registered functions will not be called for urls beginning
	 * with `/$handler/`
	 * 
	 * @param string $handler The page type identifier
	 *
	 * @return void
	 */
	function unregisterPageHandler($handler) {
		unset($this->pagehandlers[$handler]);
	}


}