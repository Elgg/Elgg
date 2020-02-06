<?php

namespace Elgg\Groups\Middleware;

use Elgg\Exceptions\Http\EntityPermissionsException;
use Elgg\Request;

/**
 * Middleware to protect group creation page when only admins can create groups
 *
 * @since 3.2
 */
class LimitedGroupCreation {

	/**
	 * Check plugin settings
	 *
	 * @param Request $request the http request
	 *
	 * @return void
	 * @throws EntityPermissionsException
	 */
	public function __invoke(Request $request) {
		
		if (elgg_is_admin_logged_in()) {
			return;
		}
		
		if (elgg_get_plugin_setting('limited_groups', 'groups') !== 'yes') {
			return;
		}
		
		throw new EntityPermissionsException(elgg_echo('groups:cantcreate'));
	}
}
