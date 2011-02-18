/**
 * Provides page owner and context functions
 *
 * @todo This is a stub. Page owners can't be fully implemented until
 * the 4 types are finished.
 */

/**
 * @return {number} The GUID of the logged in user
 */
elgg.get_page_owner_guid = function() {
	return elgg.page_owner.guid || 0;
};

