import 'jquery';
import elgg from 'elgg';
import Ajax from 'elgg/Ajax';
import system_messages from 'elgg/system_messages';
import i18n from 'elgg/i18n';

var tokenRefreshTimer = setInterval(refreshToken, elgg.security.interval);

/**
 * Updates in-page CSRF tokens that were validated on the server. Only validated __elgg_token values
 * are replaced.
 *
 * @param {Object} token_object Value to replace elgg.security.token
 * @param {Object} valid_tokens Map of valid tokens (as keys) in the current page
 * @return {void}
 */
function setToken(token_object, valid_tokens) {
	// update the convenience object
	elgg.security.token = token_object;

	// also update all forms
	$('[name=__elgg_ts]').val(token_object.__elgg_ts);
	$('[name=__elgg_token]').each(function () {
		if (valid_tokens[$(this).val()]) {
			$(this).val(token_object.__elgg_token);
		}
	});

	// also update all links that contain tokens and time stamps
	$('[href*="__elgg_ts"][href*="__elgg_token"]').each(function () {
		var token = this.href.match(/__elgg_token=([0-9a-z_-]+)/i)[1];
		if (valid_tokens[token]) {
			this.href = this.href
				.replace(/__elgg_ts=\d+/i, '__elgg_ts=' + token_object.__elgg_ts)
				.replace(/__elgg_token=[0-9a-z_-]+/i, '__elgg_token=' + token_object.__elgg_token);
		}
	});
};

/**
 * Security tokens time out so we refresh those every so often.
 *
 * We don't want to update invalid tokens, so we collect all tokens in the page and send them to
 * the server to be validated. Those that were valid are replaced in setToken().
 */
function refreshToken() {
	// round up token pairs present
	var pairs = {};

	pairs[elgg.security.token.__elgg_ts + ',' + elgg.security.token.__elgg_token] = 1;

	$('form').each(function () {
		// we need consider only the last ts/token inputs, as those will be submitted
		var ts = $('[name=__elgg_ts]:last', this).val();
		var token = $('[name=__elgg_token]:last', this).val();
		// some forms won't have tokens
		if (token) {
			pairs[ts + ',' + token] = 1;
		}
	});

	$('[href*="__elgg_ts"][href*="__elgg_token"]').each(function () {
		var ts = this.href.match(/__elgg_ts=(\d+)/i)[1];
		var token = this.href.match(/__elgg_token=([0-9a-z_-]+)/i)[1];
		pairs[ts + ',' + token] = 1;
	});

	pairs = $.map(pairs, function (val, key) {
		return key;
	});

	var ajax = new Ajax(false);
	ajax.path('refresh_token', {
		data: {
			pairs: pairs,
			session_token: elgg.session.token
		},
		success: function (data) {
			if (data) {
				elgg.session.token = data.session_token;
				setToken(data.token, data.valid_tokens);

				if (elgg.get_logged_in_user_guid() != data.user_guid) {
					elgg.session.user = null;
					elgg.user = null;
					clearInterval(tokenRefreshTimer);
					if (data.user_guid) {
						system_messages.error(i18n.echo('session_changed_user'));
					} else {
						system_messages.error(i18n.echo('session_expired'));
					}
				}
			}
		},
		error: function () {
		}
	});
};

export default {
	/**
	 * Add elgg action tokens to an object, URL, or query string (with a ?).
	 *
	 * @param {FormData|Object|string} data
	 * @return {FormData|Object|string} The new data object including action tokens
	 */
	addToken: function (data) {
	
		// 'http://example.com?data=sofar'
		if (typeof data === 'string') {
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
		if (data === undefined) {
			return elgg.security.token;
		}
	
		// {...}
		if ($.isPlainObject(data)) {
			return $.extend(data, elgg.security.token);
		}
	
		if (data instanceof FormData) {
			data.set('__elgg_ts', elgg.security.token.__elgg_ts);
			data.set('__elgg_token', elgg.security.token.__elgg_token);
			return data;
		}
	
		// oops, don't recognize that!
		throw new TypeError("addToken not implemented for " + (typeof data) + "s");
	}
};
