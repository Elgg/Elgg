<?php

namespace Elgg\WebServices;

use Elgg\DefaultPluginBootstrap;

/**
 * Bootstraps the plugin
 *
 * @since 4.0
 * @internal
 */
class Bootstrap extends DefaultPluginBootstrap {
	
	/**
	 * {@inheritDoc}
	 */
	public function init() {
		// Register a service handler for the default web services
		// The name rest is a misnomer as they are not RESTful
		elgg_ws_register_service_handler('rest', 'ws_rest_handler');
	
		// expose the list of api methods
		elgg_ws_expose_function("system.api.list", "list_all_apis", null,
			elgg_echo("system.api.list"), "GET", false, false);
	
		// The authentication token api
		elgg_ws_expose_function(
			"auth.gettoken",
			"auth_gettoken",
			[
				'username' =>  ['type' => 'string'],
				'password' =>  ['type' => 'string'],
			],
			elgg_echo('auth.gettoken'),
			'POST',
			false,
			false
		);
	}
}
