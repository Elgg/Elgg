<?php

namespace Elgg\Router\Middleware;

use Elgg\Http\ResponseBuilder;

/**
 * Some logic implemented before action is executed
 */
class ActionMiddleware {

	/**
	 * Pre-action logic
	 *
	 * @param \Elgg\Request $request Request
	 * @return ResponseBuilder|null
	 */
	public function __invoke(\Elgg\Request $request) {
		$route = $request->getRoute();
		list($prefix, $action) = explode(':', $route, 2);

		ob_start();
		$result = $request->elgg()->hooks->trigger('action', $action, null, true);
		$output = ob_get_clean();

		//  this allows you to return a ok or error response in the hook
		if ($result instanceof ResponseBuilder) {
			return $result;
		}
		
		// To quietly cancel the file, return a falsey value in the "action" hook.
		if (!$result) {
			return elgg_ok_response($output);
		}

		// set the maximum execution time for actions
		$action_timeout = $request->elgg()->config->action_time_limit;
		if (isset($action_timeout)) {
			set_time_limit($action_timeout);
		}

		return null;
	}

}
