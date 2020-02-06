<?php

namespace Elgg\Router\Middleware;

use Elgg\Exceptions\Http\ValidationException;
use Elgg\Http\ResponseBuilder;

/**
 * Some logic implemented before action is executed
 */
class ActionMiddleware {

	/**
	 * Pre-action logic
	 *
	 * @param \Elgg\Request $request Request
	 *
	 * @return ResponseBuilder|null
	 * @throws ValidationException
	 */
	public function __invoke(\Elgg\Request $request) {
		$route = $request->getRoute();
		list($prefix, $action) = explode(':', $route, 2);
		
		$hook_params = ['request' => $request];
		$result = $request->elgg()->hooks->trigger('action:validate', $action, $hook_params, true);
		if ($result === false) {
			throw new ValidationException(elgg_echo('ValidationException'));
		}

		// set the maximum execution time for actions
		$action_timeout = $request->elgg()->config->action_time_limit;
		if (isset($action_timeout)) {
			set_time_limit($action_timeout);
		}

		return null;
	}

}
