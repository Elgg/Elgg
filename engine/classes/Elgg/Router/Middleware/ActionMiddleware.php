<?php

namespace Elgg\Router\Middleware;

use Elgg\Http\ResponseBuilder;
use Elgg\ValidationException;

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

		// deprecated action handling
		$deprecated_msg = "'action', '$action' hook has been deprecated.
			Please use route middleware or 'action:validate','$action' hook";
		ob_start();
		$result = $request->elgg()->hooks->triggerDeprecated('action', $action, null, true, $deprecated_msg, '3.0');
		$output = ob_get_clean();
		
		//  this allows you to return a ok or error response in the hook
		if ($result instanceof ResponseBuilder) {
			return $result;
		}

		// To quietly cancel the file, return a falsey value in the "action" hook.
		if (!$result) {
			return elgg_ok_response($output);
		}

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
