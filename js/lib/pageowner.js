/**
 * Provides page owner and context functions
 *
 * @todo This is a stub. Page owners can't be fully implemented until
 * the 4 types are finished.
 */

/**
 * @return {number} The GUID of the page owner entity or 0 for no owner
 */
elgg.get_page_owner_guid = function() {
	if (elgg.page_owner !== undefined) {
		return elgg.page_owner.guid;
	} else {
		return 0;
	}
};

