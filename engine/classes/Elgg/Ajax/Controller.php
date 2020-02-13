<?php

namespace Elgg\Ajax;

use Elgg\Exceptions\InvalidArgumentException;
use Elgg\Http\ResponseBuilder;
use Elgg\Request;

/**
 * Controller to handle /ajax requests
 *
 * @since 4.0
 * @internal
 */
class Controller {
	
	/**
	 * Respond to a request
	 *
	 * @param Request $request the HTTP request
	 *
	 * @return ResponseBuilder
	 */
	public function __invoke(Request $request) {
		
		$segments = explode('/', $request->getParam('segments'));
		if (count($segments) < 2) {
			return elgg_error_response("Ajax pagehandler called with invalid segments", REFERRER, ELGG_HTTP_BAD_REQUEST);
		}
		
		$view = '';
		switch ($segments[0]) {
			case 'view':
				if (elgg_extract(1, $segments) === 'admin') {
					// protect admin views similar to all admin pages that are protected automatically in the admin_page_handler
					elgg_admin_gatekeeper();
				}
				// ignore 'view/'
				$view = implode('/', array_slice($segments, 1));
				break;
			case 'form':
				// form views start with "forms", not "form"
				$view = 'forms/' . implode('/', array_slice($segments, 1));
				break;
			default:
				return elgg_error_response("Ajax pagehandler called with invalid segments", REFERRER, ELGG_HTTP_BAD_REQUEST);
		}
		
		$ajax_api = _elgg_services()->ajax;
		$allowed_views = $ajax_api->getViews();
		
		// cacheable views are always allowed
		if (!in_array($view, $allowed_views) && !_elgg_services()->views->isCacheableView($view)) {
			return elgg_error_response("Ajax view '$view' was not registered", REFERRER, ELGG_HTTP_FORBIDDEN);
		}
		
		if (!elgg_view_exists($view)) {
			return elgg_error_response("Ajax view '$view' was not found", REFERRER, ELGG_HTTP_NOT_FOUND);
		}
		
		// pull out GET parameters through filter
		$vars = [];
		foreach ($request->getHttpRequest()->query->keys() as $name) {
			$vars[$name] = get_input($name);
		}
		
		if (isset($vars['guid'])) {
			$vars['entity'] = get_entity($vars['guid']);
		}
		
		if (isset($vars['river_id'])) {
			$vars['item'] = elgg_get_river_item_from_id($vars['river_id']);
		}
		
		$content_type = '';
		if ($segments[0] === 'view') {
			$output = elgg_view($view, $vars);
			
			// Try to guess the mime-type
			switch ($segments[1]) {
				case "js":
					$content_type = 'text/javascript;charset=utf-8';
					break;
				case "css":
					$content_type = 'text/css;charset=utf-8';
					break;
				default :
					if (_elgg_services()->views->isCacheableView($view)) {
						$file = _elgg_services()->views->findViewFile($view, elgg_get_viewtype());
						$content_type = 'text/html';
						try {
							$content_type = elgg()->mimetype->getMimeType($file, $content_type);
						} catch (InvalidArgumentException $e) {
							// nothing for now
						}
					}
					break;
			}
		} else {
			$action = implode('/', array_slice($segments, 1));
			$output = elgg_view_form($action, [], $vars);
		}
		
		if ($content_type) {
			elgg_set_http_header("Content-Type: $content_type");
		}
		
		return elgg_ok_response($output);
	}
}
