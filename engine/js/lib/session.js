/**
 * @todo comment
 */
elgg.provide('elgg.session');

/**
 * Helper function for setting cookies
 * @param {string} name
 * @param {string} value
 * @param {Object} options
 *  {number|Date} options[expires]
 * 	{string} options[path]
 * 	{string} options[domain]
 * 	{boolean} options[secure]
 * 
 * @return {string} The value of the cookie, if only name is specified
 */
elgg.session.cookie = function(name, value, options) {
	//elgg.session.cookie()
	if(typeof name == 'undefined') {
		return document.cookie;
	}
	
	//elgg.session.cookie(name)
	if (typeof value == 'undefined') {
		if (document.cookie && document.cookie != '') {
			var cookies = document.cookie.split(';');
			for (var i = 0; i < cookies.length; i++) {
				var cookie = jQuery.trim(cookies[i]).split('=');
				if (cookie[0] == name) {
					return decodeURIComponent(cookie[1]);
				}
			}
		}
		return undefined;
	}
	
	// elgg.session.cookie(name, value[, opts])
	var cookies = [];

	options = options || {};
	
	if (value === null) {
		value = '';
		options.expires = -1;
	}
	
	cookies.push(name + '=' + value);
	
	if (typeof options.expires == 'number') {
		var date, valid = true;
		
		if (typeof options.expires == 'number') {
			date = new Date();
			date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
		} else if(options.expires.toUTCString) {
			date = options.expires;
		} else {
			valid = false;
		}
		
		valid ? cookies.push('expires=' + date.toUTCString()) : 0;
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
 * @return {ElggUser} The logged in user
 */
elgg.get_loggedin_user = function() {
	return elgg.session.user;
};

/**
 * @return {number} The GUID of the logged in user
 */
elgg.get_loggedin_userid = function() {
	var user = elgg.get_loggedin_user();
	return user ? user.guid : 0;
};

/**
 * @return {boolean} Whether there is a user logged in
 */
elgg.isloggedin = function() {
	return (elgg.get_loggedin_user() instanceof elgg.ElggUser);
};

/**
 * @return {boolean} Whether there is an admin logged in
 */
elgg.isadminloggedin = function() {
	var user = elgg.get_loggedin_user();
	return (user instanceof ElggUser) && user.isAdmin();
};

/**
 * @deprecated Use elgg.session.cookie instead
 */
$.cookie = elgg.session.cookie;