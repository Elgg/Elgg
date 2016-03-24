/*globals elgg, $*/
elgg.provide('elgg.ajax');

/**
 * @author Evan Winslow
 * Provides a bunch of useful shortcut functions for making ajax calls
 */

/**
 * Wrapper function for jQuery.ajax which ensures that the url being called
 * is relative to the elgg site root.
 *
 * You would most likely use elgg.get or elgg.post, rather than this function
 *
 * @param {string} url Optionally specify the url as the first argument
 * @param {Object} options Optional. {@link jQuery#ajax}
 * @return {jqXHR}
 */
elgg.ajax = function(url, options) {
	options = elgg.ajax.handleOptions(url, options);

	options.url = elgg.normalize_url(options.url);
	return $.ajax(options);
};
/**
 * @const
 */
elgg.ajax.SUCCESS = 0;

/**
 * @const
 */
elgg.ajax.ERROR = -1;

/**
 * Handle optional arguments and return the resulting options object
 *
 * @param url
 * @param options
 * @return {Object}
 * @private
 */
elgg.ajax.handleOptions = function(url, options) {
	var data_only = true,
		data,
		member;

	//elgg.ajax('example/file.php', {...});
	if (elgg.isString(url)) {
		options = options || {};

	//elgg.ajax({...});
	} else {
		options = url || {};
		url = options.url;
	}

	//elgg.ajax('example/file.php', function() {...});
	if (elgg.isFunction(options)) {
		data_only = false;
		options = {success: options};
	}

	//elgg.ajax('example/file.php', {data:{...}});
	if (options.data) {
		data_only = false;
	} else {
		for (member in options) {
			//elgg.ajax('example/file.php', {callback:function(){...}});
			if (elgg.isFunction(options[member])) {
				data_only = false;
			}
		}
	}

	//elgg.ajax('example/file.php', {notdata:notfunc});
	if (data_only) {
		data = options;
		options = {data: data};
	}

	if (!elgg.isFunction(options.error)) {
		// add a generic error handler
		options.error = elgg.ajax.handleAjaxError;
	}
	
	if (url) {
		options.url = url;
	}

	return options;
};

/**
 * Handles low level errors like 404
 *
 * @param xhr
 * @param status
 * @param error
 * @private
 */
elgg.ajax.handleAjaxError = function(xhr, status, error) {
	if (!xhr.getAllResponseHeaders()) {
		// user aborts (like refresh or navigate) do not have headers
		return;
	}
	
	elgg.register_error(elgg.echo('ajax:error'));
};

/**
 * Wrapper function for elgg.ajax which forces the request type to 'get.'
 *
 * @param {string} url Optionally specify the url as the first argument
 * @param {Object} options {@link jQuery#ajax}
 * @return {jqXHR}
 */
elgg.get = function(url, options) {
	options = elgg.ajax.handleOptions(url, options);

	options.type = 'get';
	return elgg.ajax(options);
};

/**
 * Wrapper function for elgg.get which forces the dataType to 'json.'
 *
 * @param {string} url Optionally specify the url as the first argument
 * @param {Object} options {@link jQuery#ajax}
 * @return {jqXHR}
 */
elgg.getJSON = function(url, options) {
	options = elgg.ajax.handleOptions(url, options);

	options.dataType = 'json';
	return elgg.get(options);
};

/**
 * Wrapper function for elgg.ajax which forces the request type to 'post.'
 *
 * @param {string} url Optionally specify the url as the first argument
 * @param {Object} options {@link jQuery#ajax}
 * @return {jqXHR}
 */
elgg.post = function(url, options) {
	options = elgg.ajax.handleOptions(url, options);

	options.type = 'post';
	return elgg.ajax(options);
};

/**
 * Perform an action via ajax
 *
 * @example Usage 1:
 * At its simplest, only the action name is required (and anything more than the
 * action name will be invalid).
 * <pre>
 * elgg.action('name/of/action');
 * </pre>
 *
 * The action can be relative to the current site ('name/of/action') or
 * the full URL of the action ('http://elgg.org/action/name/of/action').
 *
 * @example Usage 2:
 * If you want to pass some data along with it, use the second parameter
 * <pre>
 * elgg.action('friend/add', { friend: some_guid });
 * </pre>
 *
 * @example Usage 3:
 * Of course, you will have no control over what happens when the request
 * completes if you do it like that, so there's also the most verbose method
 * <pre>
 * elgg.action('friend/add', {
 *     data: {
 *         friend: some_guid
 *     },
 *     success: function(json) {
 *         //do something
 *     },
 * }
 * </pre>
 * You can pass any of your favorite $.ajax arguments into this second parameter.
 *
 * @note If you intend to use the second field in the "verbose" way, you must
 * specify a callback method or the data parameter.  If you do not, elgg.action
 * will think you mean to send the second parameter as data.
 *
 * @note You do not have to add security tokens to this request.  Elgg does that
 * for you automatically.
 *
 * @see jQuery.ajax
 *
 * @param {String} action The action to call.
 * @param {Object} options
 * @return {jqXHR}
 */
elgg.action = function(action, options) {
	elgg.assertTypeOf('string', action);

	// support shortcut and full URLs
	// this will mangle URLs that aren't elgg actions.
	// Use post, get, or ajax for those.
	if (action.indexOf('action/') < 0) {
		action = 'action/' + action;
	}

	options = elgg.ajax.handleOptions(action, options);

	// This is a misuse of elgg.security.addToken() because it is not always a
	// full query string with a ?. As such we need a special check for the tokens.
	if (!elgg.isString(options.data) || options.data.indexOf('__elgg_ts') == -1) {
		options.data = elgg.security.addToken(options.data);
	}
	options.dataType = 'json';

	//Always display system messages after actions
	var custom_success = options.success || elgg.nullFunction;
	options.success = function(json, two, three, four) {
		if (json && json.system_messages) {
			elgg.register_error(json.system_messages.error);
			elgg.system_message(json.system_messages.success);
		}

		custom_success(json, two, three, four);
	};

	return elgg.post(options);
};

/**
 * Make an API call
 *
 * @example Usage:
 * <pre>
 * elgg.api('system.api.list', {
 *     success: function(data) {
 *         console.log(data);
 *     }
 * });
 * </pre>
 *
 * @param {String} method The API method to be called
 * @param {Object} options {@link jQuery#ajax}
 * @return {jqXHR}
 */
elgg.api = function (method, options) {
	elgg.assertTypeOf('string', method);

	var defaults = {
		dataType: 'json',
		data: {}
	};

	options = elgg.ajax.handleOptions(method, options);
	options = $.extend(defaults, options);

	options.url = 'services/api/rest/' + options.dataType + '/';
	options.data.method = method;

	return elgg.ajax(options);
};
