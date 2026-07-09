<?php

namespace Elgg\Helpers\Controllers;

use Elgg\Controllers\GenericContentListing;

/**
 * For testing the GenericContentListing controller
 *
 * @internal
 */
class GenericContentListingTesting extends GenericContentListing {
	
	/**
	 * Create controller
	 *
	 * @param \Elgg\Request $request Elgg request
	 */
	public function __construct(\Elgg\Request $request) {
		$this->request = $request;
		$this->route = $request->getHttpRequest()?->getRoute();
	}
	
	/**
	 * Sets the page owner
	 *
	 * @param \ElggUser $user new page owner
	 *
	 * @return void
	 */
	public function setPageOwner(\ElggUser $user): void {
		$this->page_owner = $user;
	}
}
