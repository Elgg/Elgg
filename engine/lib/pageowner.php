<?php
/**
 * Elgg page owner library
 */

/**
 * Gets the guid of the entity that owns the current page.
 *
 * @return int The current page owner guid (0 if none).
 * @since 1.8.0
 */
function elgg_get_page_owner_guid(): int {
	return _elgg_services()->pageOwner->getPageOwnerGuid();
}

/**
 * Gets the owner entity for the current page.
 *
 * @return \ElggEntity|null The current page owner or null if none.
 *
 * @since 1.8.0
 */
function elgg_get_page_owner_entity(): ?\ElggEntity {
	return _elgg_services()->pageOwner->getPageOwnerEntity();
}

/**
 * Set the guid of the entity that owns this page
 *
 * @param int $guid The guid of the page owner
 * @return void
 * @since 1.8.0
 */
function elgg_set_page_owner_guid(int $guid): void {
	$page_owner = _elgg_services()->pageOwner;
	
	if ($guid >= 0) {
		$page_owner->setPageOwnerGuid($guid);
		return;
	}
	
	// removes page owner
	$page_owner->setPageOwnerGuid(0);
}
