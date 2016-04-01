define(function (require) {
	var $ = require('jquery');
	var elgg = require('elgg');
	var spinner = require('elgg/spinner');

	var site_url = elgg.get_site_url(),
		action_base = site_url + 'action/',
		fragment_pattern = /#.*$/,
		query_pattern = /\?.*$/,
		leading_slash_pattern = /^\//,
		slashes_pattern = /(^\/|\/$)/g;

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
		 *     data  : {Object} Data to send to the server (optional). If set to a string (e.g. $.serialize)
		 *                      then the request hook will not be called.
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

			if (!$.isPlainObject(options)) {
				throw new Error('options must be a plain object with key "url"');
			}
			if (!options.url && hook_type !== 'path:') {
				throw new Error('options must be a plain object with key "url"');
			}

			// ease hook filtering by making these keys always available
			if (options.data === undefined) {
				options.data = {};
			}
			if ($.isPlainObject(options.data)) {
				options.data = options.data || {};
			} else {
				if (typeof options.data !== 'string') {
					throw new Error('if defined, options.data must be a plain object or string');
				}
			}

			options.dataType = 'json';

			// copy of original options
			orig_options = $.extend({}, options);

			params = {
				options: options
			};
			if (hook_type && typeof options.data !== 'string') {
				options.data = elgg.trigger_hook(Ajax.REQUEST_DATA_HOOK, hook_type, params, options.data);
			}

			// we do this here because hook may have made data non-empty, in which case we'd need to
			// default to POST
			if (!options.method) {
				options.method = 'GET';
				if (options.data && !$.isEmptyObject(options.data)) {
					options.method = 'POST';
				}
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
		 * @param {String} path    path or URL. e.g. "foo/bar"
		 * @param {Object} options {@link jQuery#ajax}. See fetch()
		 * @returns {jqXHR}
		 */
		this.path = function (path, options) {
			elgg.assertTypeOf('string', path);

			// https://example.org/elgg/foo/?arg=1#bar => foo/?arg=1
			if (path.indexOf(site_url) === 0) {
				path = path.substr(site_url.length);
			}
			path = path.replace(fragment_pattern, '');

			assertNotUrl(path);

			options = options || {};
			options.url = path;

			// /foo/?arg=1 => foo
			path = path.replace(query_pattern, '').replace(slashes_pattern, '');

			return fetch(options, 'path:' + path);
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
			if (view === '') {
				throw new Error('view cannot be empty');
			}

			assertNotUrl(view);

			options = options || {};
			options.url = 'ajax/view/' + view;

			// remove query
			view = view.replace(query_pattern, '').replace(slashes_pattern, '');

			return fetch(options, 'view:' + view);
		};

		/**
		 * Fetch a form rendered on the server
		 *
		 * See fetch() for more options.
		 *
		 * @param {String} action  Action name or URL
		 * @param {Object} options {@link jQuery#ajax}. See fetch()
		 * @returns {jqXHR}
		 */
		this.form = function (action, options) {
			elgg.assertTypeOf('string', action);
			if (action === '') {
				throw new Error('action cannot be empty');
			}

			action = action.replace(leading_slash_pattern, '').replace(fragment_pattern, '');

			assertNotUrl(action);

			options = options || {};
			options.url = 'ajax/form/' + action;

			// remove query
			action = action.replace(query_pattern, '').replace(slashes_pattern, '');

			return fetch(options, 'form:' + action);
		};

		/**
		 * Perform an action
		 *
		 * See fetch() for more options.
		 *
		 * @param {String} action  Action name or URL
		 * @param {Object} options jQuery.ajax options. See fetch()
		 * @returns {jqXHR}
		 */
		this.action = function (action, options) {
			elgg.assertTypeOf('string', action);
			if (action === '') {
				throw new Error('action cannot be empty');
			}

			// https://example.org/elgg/action/foo/?arg=1#bar => foo/?arg=1
			if (action.indexOf(action_base) === 0) {
				action = action.substr(action_base.length);
			}
			action = action.replace(leading_slash_pattern, '').replace(fragment_pattern, '');

			assertNotUrl(action);

			options = options || {};
			options.data = options.data || {};

			// add tokens?
			var m = action.match(/\?(.+)$/);
			if (m && /(^|&)__elgg_ts=/.test(m[1])) {
				// token will be in the URL
			} else {
				options.data = elgg.security.addToken(options.data);
			}

			options.method = options.method || 'POST';
			options.url = 'action/' + action;

			// /foo/?arg=1 => foo
			action = action.replace(query_pattern, '').replace(slashes_pattern, '');

			return fetch(options, 'action:' + action);
		};

		/**
		 * Convert a form/element to a data object. Use this instead of $.serialize to allow other plugins
		 * to alter the request by plugin hook.
		 *
		 * @param {*} el HTML element or CSS selector
		 * @returns {Object}
		 */
		this.objectify = function (el) {
			// http://stackoverflow.com/a/1186309/3779
			var o = {};
			var a = $(el).serializeArray();

			$.each(a, function() {
				if (o[this.name] !== undefined) {
					if (!o[this.name].push) {
						o[this.name] = [o[this.name]];
					}
					o[this.name].push(this.value || '');
				} else {
					o[this.name] = this.value || '';
				}
			});

			return o;
		};
	}

	/**
	 * Throw if this looks like a URL.
	 *
	 * @param {String} arg
	 */
	function assertNotUrl(arg) {
		if (/^https?:/.test(arg)) {
			throw new Error('elgg/Ajax cannot be used with external URLs');
		}
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

	/**
	 * Sets up response hook for all responses
	 * @private For testing
	 */
	Ajax._init_hooks = function () {
		elgg.register_hook_handler(Ajax.RESPONSE_DATA_HOOK, 'all', function (name, type, params, data) {
			var m = data._elgg_msgs;
			m && m.error && elgg.register_error(m.error);
			m && m.success && elgg.system_message(m.success);
			delete data._elgg_msgs;

			var deps = data._elgg_deps;
			deps && deps.length && Ajax._require(deps);
			delete data._elgg_deps;

			return data;
		});
	};

	Ajax._init_hooks();

	/**
	 * @private For testing
	 */
	Ajax._require = require;

	return Ajax;
});
