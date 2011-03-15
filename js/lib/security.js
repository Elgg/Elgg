/**
 * Hold security-related data here
 */
elgg.provide('elgg.security');

elgg.security.token = {};

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

	//also update all links
	$('[href]').each(function() {
		this.href = this.href
			.replace(/__elgg_ts=\d*/, '__elgg_ts=' + json.__elgg_ts)
			.replace(/__elgg_token=[0-9a-f]*/, '__elgg_token=' + json.__elgg_token);
	});
};

/**
 * Security tokens time out, so lets refresh those every so often.
 * 
 * @todo handle error and bad return data
 */
elgg.security.refreshToken = function() {
	elgg.action('security/refreshtoken', function(data) {
		elgg.security.setToken(data.output);
	});
};


/**
 * Add elgg action tokens to an object or string (assumed to be url data)
 *
 * @param {Object|string} data
 * @return {Object} The new data object including action tokens
 * @private
 */
elgg.security.addToken = function(data) {

	// 'http://example.com?data=sofar'
	if (elgg.isString(data)) {
		var args = [];
		if (data) {
			args.push(data);
		}
		args.push("__elgg_ts=" + elgg.security.token.__elgg_ts);
		args.push("__elgg_token=" + elgg.security.token.__elgg_token);

		return args.join('&');
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
	//refresh security token every 5 minutes
	//this is set in the js/elgg PHP view.
	setInterval(elgg.security.refreshToken, elgg.security.interval);
};

elgg.register_hook_handler('boot', 'system', elgg.security.init);