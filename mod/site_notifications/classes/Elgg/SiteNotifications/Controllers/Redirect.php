<?php

namespace Elgg\SiteNotifications\Controllers;

use Elgg\Exceptions\Http\EntityPermissionsException;

/**
 * Handler for the 'redirect:object:site_notification' route
 *
 * Redirects to the url of the notification
 *
 * @since 4.0
 * @internal
 */
class Redirect {
	
	/**
	 * Redirect to the correct url and mark the notification as read
	 *
	 * @param \Elgg\Request $request the current page request
	 *
	 * @return \Elgg\Http\Response
	 * @throws EntityPermissionsException
	 */
	public function __invoke(\Elgg\Request $request) {
		$entity = $request->getEntityParam();
		if (!$entity instanceof \SiteNotification || !$entity->canEdit()) {
			throw new EntityPermissionsException();
		}
		
		$entity->read = true;
		
		return elgg_redirect_response($entity->getURL());
	}
}
