define(function (require) {
	var $ = require('jquery');
	var elgg = require('elgg');
	var spinner = require('elgg/spinner');

	var spinner_enabled = true;

	/**
	 * Fetch a value from an Ajax endpoint.
	 *
	 * Note that this function does not support the array form of "success".
	 *
	 * To request the response be cached, set options.data.response_ttl to a number of seconds.
	 *
	 * @param {Object} options   {@link jQuery#ajax}. The default method is "GET".
	 *
	 *     url : {String} Path of the Ajax API endpoint (required)
	 *
	 * @param {String} hook_type Type of the plugin hooks. If missing, the hooks will not trigger.
	 *
	 * @returns {jqXHR}
	 */
	function fetch(options, hook_type) {
		var orig_options,
			msgs_were_set = 0,
			params;

		function unwrap_data(data) {
			var params;

			if (!msgs_were_set) {
				data.msgs.error && elgg.register_error(data.msgs.error);
				data.msgs.success && elgg.system_message(data.msgs.success);
				msgs_were_set = 1;
			}

			params = {
				options: orig_options
			};
			if (hook_type) {
				return elgg.trigger_hook(ajax.RESPONSE_DATA_HOOK, hook_type, params, data.data);
			}
			return data.data;
		}

		hook_type = hook_type || '';

		if (!$.isPlainObject(options) || !options.url) {
			throw new Error('options must be a plain with key "url"');
		}

		// ease hook filtering by making these keys always available
		options.data = options.data || {};
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
			options.data = elgg.trigger_hook(ajax.REQUEST_DATA_HOOK, hook_type, params, options.data);
		}

		if ($.isArray(options.success)) {
			throw new Error('The array form of options.success is not supported');
		}

		if (!elgg.isFunction(options.error)) {
			// add a generic error handler
			options.error = function(xhr, status, error) {
				elgg.ajax.handleAjaxError(xhr, status, error);
			};
		}

		if (elgg.isFunction(options.success)) {
			options.success = function (data) {
				data = unwrap_data(data);
				orig_options.success(data);
			};
		}

		if (spinner_enabled) {
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
			'X-Elgg': '2'
		};

		return $.ajax(options).then(unwrap_data);
	}

	// public API
	var ajax = {
		/**
		 * The options.data will be passed through this hook, with the endpoint name as hook type and
		 * params.option will have a copy of the original options object
		 */
		REQUEST_DATA_HOOK: 'ajax_request_data',

		/**
		 * The returned data will be passed through this hook, with the endpoint name as hook type and
		 * params.option will have a copy of the original options object.
		 *
		 * Note this hook will be triggered twice if you provide an options.success function.
		 */
		RESPONSE_DATA_HOOK: 'ajax_response_data',

		/**
		 * Fetch content from a page handler
		 *
		 * @param {String} path    URL path. e.g. "foo/bar"
		 * @param {Object} options {@link jQuery#ajax}. See fetch()
		 * @returns {jqXHR}
		 */
		fetchPath: function (path, options) {
			elgg.assertTypeOf('string', path);

			options = options || {};
			options.url = path;

			return fetch(options, 'path:' + path.replace(/\/$/, ''));
		},

		/**
		 * Fetch content from a view registered for Ajax
		 *
		 * @param {String} view    View name to fetch
		 * @param {Object} options {@link jQuery#ajax}. See fetch()
		 * @returns {jqXHR}
		 */
		fetchView: function (view, options) {
			elgg.assertTypeOf('string', view);

			options = options || {};
			options.url = 'ajax/view/' + view;

			return fetch(options, 'view:' + view);
		},

		/**
		 * Fetch a form rendered on the server
		 *
		 * @param {String} action  Action name
		 * @param {Object} options {@link jQuery#ajax}. See fetch()
		 * @returns {jqXHR}
		 */
		fetchForm: function (action, options) {
			elgg.assertTypeOf('string', action);

			options = options || {};
			options.url = 'ajax/form/' + action;

			return fetch(options, 'form:' + action);
		},

		/**
		 * Perform an action
		 *
		 * @param {String} action  Action name
		 * @param {Object} options jQuery.ajax options. The default method is "POST". See fetch()
		 * @returns {jqXHR}
		 */
		performAction: function (action, options) {
			elgg.assertTypeOf('string', action);

			options = options || {};
			options.url = 'action/' + action;
			options.data = elgg.security.addToken(options.data || {});
			options.method = options.method || 'POST';

			return fetch(options, 'action:' + action);
		},

		/**
		 * Set whether Ajax functions should activate the page-level loading spinner
		 *
		 * @param {Bool} enabled
		 * @return void
		 */
		setEnableSpinner: function (enabled) {
			spinner_enabled = !!enabled;
		}
	};

	return ajax;
});
