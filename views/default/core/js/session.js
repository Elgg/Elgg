/**
 * Returns the object of the user logged in.
 *
 * @return {ElggUser} The logged in user
 */
elgg.get_logged_in_user_entity = function() {
	return elgg.session.user;
};

/**
 * Returns the GUID of the logged in user or 0.
 *
 * @return {number} The GUID of the logged in user
 */
elgg.get_logged_in_user_guid = function() {
	var user = elgg.get_logged_in_user_entity();
	return user ? user.guid : 0;
};

/**
 * Returns if a user is logged in.
 *
 * @return {boolean} Whether there is a user logged in
 */
elgg.is_logged_in = function() {
	return (elgg.get_logged_in_user_entity() instanceof elgg.ElggUser);
};

/**
 * Returns if the currently logged in user is an admin.
 *
 * @return {boolean} Whether there is an admin logged in
 */
elgg.is_admin_logged_in = function() {
	var user = elgg.get_logged_in_user_entity();
	return (user instanceof elgg.ElggUser) && user.isAdmin();
};

// This just has to happen after ElggUser is defined, however it's probably
// better to have this procedural code here than in ElggUser.js
if (elgg.session.user) {
	elgg.session.user = new elgg.ElggUser(elgg.session.user);
}

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
