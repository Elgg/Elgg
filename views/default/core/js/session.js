/**
 * Provides session methods.
 */
elgg.provide('elgg.session');

/**
 * Helper function for setting cookies
 * @param {string} name
 * @param {string} value
 * @param {Object} options
 *
 * {number|Date} options[expires]
 * {string} options[path]
 * {string} options[domain]
 * {boolean} options[secure]
 *
 * @return {string|undefined} The value of the cookie, if only name is specified. Undefined if no value set
 */
elgg.session.cookie = function(name, value, options) {
	var cookies = [], cookie = [], i = 0, date, valid = true;
	
	//elgg.session.cookie()
	if (elgg.isUndefined(name)) {
		return document.cookie;
	}
	
	//elgg.session.cookie(name)
	if (elgg.isUndefined(value)) {
		if (document.cookie && document.cookie !== '') {
			cookies = document.cookie.split(';');
			for (i = 0; i < cookies.length; i += 1) {
				cookie = jQuery.trim(cookies[i]).split('=');
				if (cookie[0] === name) {
					return decodeURIComponent(cookie[1]);
				}
			}
		}
		return undefined;
	}
	
	// elgg.session.cookie(name, value[, opts])
	options = options || {};
	
	if (elgg.isNull(value)) {
		value = '';
		options.expires = -1;
	}
	
	cookies.push(name + '=' + value);

	if (options.expires) {
		if (elgg.isNumber(options.expires)) {
			date = new Date();
			date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
		} else if (options.expires.toUTCString) {
			date = options.expires;
		}

		if (date) {
			cookies.push('expires=' + date.toUTCString());
		}
	}
	
	// CAUTION: Needed to parenthesize options.path and options.domain
	// in the following expressions, otherwise they evaluate to undefined
	// in the packed version for some reason.
	if (options.path) {
		cookies.push('path=' + (options.path));
	}

	if (options.domain) {
		cookies.push('domain=' + (options.domain));
	}
	
	if (options.secure) {
		cookies.push('secure');
	}
	
	document.cookie = cookies.join('; ');
};

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
