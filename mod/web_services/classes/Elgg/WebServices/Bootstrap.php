<?php

namespace Elgg\WebServices;

use Elgg\DefaultPluginBootstrap;
use Elgg\WebServices\ApiMethods\SystemApiList;
use Elgg\WebServices\ApiMethods\AuthGetToken;

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
		// expose the list of api methods
		elgg_ws_expose_function('system.api.list', SystemApiList::class, null, elgg_echo('system.api.list'), 'GET', false, false);
	
		// The authentication token api
		elgg_ws_expose_function(
			'auth.gettoken',
			AuthGetToken::class,
			[
				'username' => ['type' => 'string'],
				'password' => ['type' => 'string'],
			],
			elgg_echo('auth.gettoken'),
			'POST',
			false,
			false
		);
	}
}
