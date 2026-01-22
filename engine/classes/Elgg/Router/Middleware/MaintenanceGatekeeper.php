<?php

namespace Elgg\Router\Middleware;

use Elgg\Request;

/**
 * Protects a route if site is in maintenance mode
 */
class MaintenanceGatekeeper {

	/**
	 * Gatekeeper
	 *
	 * @param Request $request Request
	 *
	 * @return \Elgg\Http\OkResponse|\Elgg\Http\ErrorResponse|void
	 */
	public function __invoke(Request $request) {
		if ($request->elgg()->session_manager->isAdminLoggedIn()) {
			return;
		}
		
		if (!$request->elgg()->config->elgg_maintenance_mode) {
			return;
		}
		
		// check event
		if (self::allowCurrentUrl($request)) {
			return;
		}
		
		$route_name = $request->getRoute();
		if (!empty($route_name) && str_starts_with($route_name, 'action:')) {
			if ($this->isAllowedAction($request)) {
				return;
			}
			
			return elgg_error_response(elgg_echo('actionunauthorized'));
		}
		
		$response = elgg_ok_response(elgg_view_resource('maintenance'), '', null, ELGG_HTTP_SERVICE_UNAVAILABLE);
		
		_elgg_services()->responseFactory->respondFromContent($response);
		
		return $response;
	}
	
	/**
	 * Checks if current action is allowed. Currently only allows login action for admin users.
	 *
	 * @param Request $request Request
	 *
	 * @return bool
	 */
	protected function isAllowedAction(Request $request): bool {
		$route = $request->getRoute();
		if ($route !== 'action:login') {
			return false;
		}

		$user = elgg_get_user_by_username((string) $request->getParam('username'), true);
		if ($user instanceof \ElggUser && $user->isAdmin()) {
			return true;
		}
		
		return false;
	}
	
	/**
	 * When in maintenance mode, should the current URL be handled normally?
	 *
	 * @param Request $request Request
	 *
	 * @return bool
	 */
	protected static function allowCurrentUrl(Request $request): bool {
		$current_url = $request->getURL();
		$site_path = preg_replace('/^https?/', '', elgg_get_site_url());
		$current_path = preg_replace('/^https?/', '', $current_url);
		if (elgg_strpos($current_path, $site_path) === 0) {
			$current_path = ($current_path === $site_path) ? '' : elgg_substr($current_path, elgg_strlen($site_path));
		} else {
			$current_path = false;
		}
	
		// allow plugins to control access for specific URLs/paths
		$params = [
			'request' => $request,
			'current_path' => $current_path,
			'current_url' => $current_url,
		];
		
		return (bool) elgg_trigger_event_results('maintenance:allow', 'url', $params, false);
	}
}
