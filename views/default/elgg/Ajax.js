define(function (require) {
	var $ = require('jquery');
	var elgg = require('elgg');
	var spinner = require('elgg/spinner');

	/**
	 * Module constructor
	 *
	 * @param {Boolean} use_spinner Use the elgg/spinner module during requests (default true)
	 *
	 * @constructor
	 */
	function Ajax(use_spinner) {

		use_spinner = elgg.isNullOrUndefined(use_spinner) ? true : !!use_spinner;

		/**
		 * Fetch a value from an Ajax endpoint.
		 *
		 * Note that this function does not support the array form of "success".
		 *
		 * To request the response be cached, set options.method to "GET" and options.data.elgg_response_ttl
		 * to a number of seconds.
		 *
		 * To bypass downloading system messages with the response, set options.data.elgg_fetch_messages = 0.
		 *
		 * @param {Object} options   See {@link jQuery#ajax}. The default method is "GET" (or "POST" for actions).
		 *
		 *     url   : {String} Path of the Ajax API endpoint (required)
		 *     error : {Function} Error handler. Default is elgg.ajax.handleAjaxError. To cancel this altogether,
		 *                        pass in function(){}.
		 *     data  : {Object} Data to send to the server (optional). Unlike jQuery, you cannot set this
		 *                      to a string.
		 *
		 * @param {String} hook_type Type of the plugin hooks. If missing, the hooks will not trigger.
		 *
		 * @returns {jqXHR}
		 */
		function fetch(options, hook_type) {
			var orig_options,
				params,
				unwrapped = false,
				result;

			function unwrap_data(data) {
				// between the deferred and a success function, make sure this runs only once.
				if (!unwrapped) {
					var params = {
						options: orig_options
					};
					if (hook_type) {
						data = elgg.trigger_hook(Ajax.RESPONSE_DATA_HOOK, hook_type, params, data);
					}
					result = data.value;
					unwrapped = true;
				}
				return result;
			}

			hook_type = hook_type || '';

			if (!$.isPlainObject(options) || !options.url) {
				throw new Error('options must be a plain object with key "url"');
			}

			// ease hook filtering by making these keys always available
			if (options.data === undefined || $.isPlainObject(options.data)) {
				options.data = options.data || {};
			} else {
				throw new Error('if defined, options.data must be a plain object');
			}

			options.dataType = 'json';
			if (!options.method) {
				options.method = 'GET';
			}

			// copy of original options
			orig_options = $.extend({}, options);

			params = {
				options: options
			};
			if (hook_type) {
				options.data = elgg.trigger_hook(Ajax.REQUEST_DATA_HOOK, hook_type, params, options.data);
			}

			if ($.isArray(options.success)) {
				throw new Error('The array form of options.success is not supported');
			}

			if (elgg.isFunction(options.success)) {
				options.success = function (data) {
					data = unwrap_data(data);
					orig_options.success(data);
				};
			}

			if (use_spinner) {
				options.beforeSend = function () {
					orig_options.beforeSend && orig_options.beforeSend();
					spinner.start();
				};
				options.complete = function () {
					spinner.stop();
					orig_options.complete && orig_options.complete();
				};
			}

			if (!options.error) {
				options.error = elgg.ajax.handleAjaxError;
			}

			options.url = elgg.normalize_url(options.url);
			options.headers = {
				'X-Elgg-Ajax-API': '2'
			};

			return $.ajax(options).then(unwrap_data);
		}

		/**
		 * Fetch content from a page handler
		 *
		 * See fetch() for more options.
		 *
		 * @param {String} path    URL path. e.g. "foo/bar"
		 * @param {Object} options {@link jQuery#ajax}. See fetch()
		 * @returns {jqXHR}
		 */
		this.path = function (path, options) {
			elgg.assertTypeOf('string', path);

			options = options || {};
			options.url = path;

			return fetch(options, 'path:' + path.replace(/\/$/, ''));
		};

		/**
		 * Fetch content from a view registered for Ajax
		 *
		 * See fetch() for more options.
		 *
		 * @param {String} view    View name to fetch
		 * @param {Object} options {@link jQuery#ajax}. See fetch()
		 * @returns {jqXHR}
		 */
		this.view = function (view, options) {
			elgg.assertTypeOf('string', view);

			options = options || {};
			options.url = 'ajax/view/' + view;

			return fetch(options, 'view:' + view);
		};

		/**
		 * Fetch a form rendered on the server
		 *
		 * See fetch() for more options.
		 *
		 * @param {String} action  Action name
		 * @param {Object} options {@link jQuery#ajax}. See fetch()
		 * @returns {jqXHR}
		 */
		this.form = function (action, options) {
			elgg.assertTypeOf('string', action);

			options = options || {};
			options.url = 'ajax/form/' + action;

			return fetch(options, 'form:' + action);
		};

		/**
		 * Perform an action
		 *
		 * See fetch() for more options.
		 *
		 * @param {String} action  Action name
		 * @param {Object} options jQuery.ajax options. See fetch()
		 * @returns {jqXHR}
		 */
		this.action = function (action, options) {
			elgg.assertTypeOf('string', action);

			options = options || {};
			options.url = 'action/' + action;
			options.data = elgg.security.addToken(options.data || {});
			options.method = options.method || 'POST';

			return fetch(options, 'action:' + action);
		};
	}

	/**
	 * The options.data will be passed through this hook, with the endpoint name as hook type and
	 * params.option will have a copy of the original options object
	 */
	Ajax.REQUEST_DATA_HOOK = 'ajax_request_data';

	/**
	 * The returned data object will be passed through this hook, with the endpoint name as hook type
	 * and params.option will have a copy of the original options object.
	 *
	 * data.value will be returned to the caller.
	 */
	Ajax.RESPONSE_DATA_HOOK = 'ajax_response_data';

	// handle system messages
	elgg.register_hook_handler(Ajax.RESPONSE_DATA_HOOK, 'all', function (name, type, params, data) {
		var m = data._elgg_msgs;
		m && m.error && elgg.register_error(m.error);
		m && m.success && elgg.system_message(m.success);
		delete data._elgg_msgs;
		return data;
	});

	return Ajax;
});
