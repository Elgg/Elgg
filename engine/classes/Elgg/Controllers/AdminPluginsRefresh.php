<?php

namespace Elgg\Controllers;

use Elgg\Request;
use Elgg\Http\ResponseBuilder;

/**
 * Controller to handle /admin_plugins_refresh requests
 *
 * @since 4.0
 * @internal
 */
class AdminPluginsRefresh {
	
	/**
	 * Respond to a request
	 *
	 * @param Request $request the HTTP request
	 *
	 * @return ResponseBuilder
	 */
	public function __invoke(Request $request) {
		elgg_set_context('admin');
		
		return elgg_ok_response([
			'list' => elgg_view('admin/plugins', [
				'list_only' => true,
			]),
			'sidebar' => elgg_view('admin/sidebar'),
		]);
	}
}
