/**
 * Hold security-related data here
 */
elgg.provide('elgg.security.token');

elgg.security.tokenRefreshFailed = false;

elgg.security.tokenRefreshTimer = null;

/**
 * Sets the currently active security token and updates all forms and links on the current page.
 *
 * @param {Object} json The json representation of a token containing __elgg_ts and __elgg_token
 * @return {Void}
 */
elgg.security.setToken = function(json) {
	//update the convenience object
	elgg.security.token = json;

	//also update all forms
	$('[name=__elgg_ts]').val(json.__elgg_ts);
	$('[name=__elgg_token]').val(json.__elgg_token);

	// also update all links that contain tokens and time stamps
	$('[href*="__elgg_ts"][href*="__elgg_token"]').each(function() {
		this.href = this.href
			.replace(/__elgg_ts=\d*/, '__elgg_ts=' + json.__elgg_ts)
			.replace(/__elgg_token=[0-9a-f]*/, '__elgg_token=' + json.__elgg_token);
	});
};

/**
 * Security tokens time out so we refresh those every so often.
 *
 * @private
 */
elgg.security.refreshToken = function() {
	elgg.getJSON('refresh_token', function(data) {
		if (data && data.__elgg_ts && data.__elgg_token) {
			elgg.security.setToken(data);
			if (elgg.is_logged_in() && data.logged_in === false) {
				elgg.session.user = null;
				elgg.register_error(elgg.echo('session_expired'));
			}
		}
	});
};


/**
 * Add elgg action tokens to an object, URL, or query string (with a ?).
 *
 * @param {Object|string} data
 * @return {Object} The new data object including action tokens
 * @private
 */
elgg.security.addToken = function(data) {

	// 'http://example.com?data=sofar'
	if (elgg.isString(data)) {
		// is this a full URL, relative URL, or just the query string?
		var parts = elgg.parse_url(data),
			args = {},
			base = '';

		if (parts['host'] === undefined) {
			if (data.indexOf('?') === 0) {
				// query string
				base = '?';
				args = elgg.parse_str(parts['query']);
			}
		} else {
			// full or relative URL

			if (parts['query'] !== undefined) {
				// with query string
				args = elgg.parse_str(parts['query']);
			}
			var split = data.split('?');
			base = split[0] + '?';
		}
		args["__elgg_ts"] = elgg.security.token.__elgg_ts;
		args["__elgg_token"] = elgg.security.token.__elgg_token;

		return base + jQuery.param(args);
	}

	// no input!  acts like a getter
	if (elgg.isUndefined(data)) {
		return elgg.security.token;
	}

	// {...}
	if (elgg.isPlainObject(data)) {
		return elgg.extend(data, elgg.security.token);
	}

	// oops, don't recognize that!
	throw new TypeError("elgg.security.addToken not implemented for " + (typeof data) + "s");
};

elgg.security.init = function() {
	// elgg.security.interval is set in the js/elgg PHP view.
	elgg.security.tokenRefreshTimer = setInterval(elgg.security.refreshToken, elgg.security.interval);
};

elgg.register_hook_handler('boot', 'system', elgg.security.init);
