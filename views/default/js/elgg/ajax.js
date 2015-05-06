define(function (require) {
	var $ = require('jquery');
	var elgg = require('elgg');
	var spinner = require('elgg/spinner');

	var spinner_enabled = true;
	var ajax = {
		/**
		 * The options.data will be passed through this hook, with the endpoint name as hook type and
		 * params.option will have a copy of the original options object
		 */
		REQUEST_DATA_HOOK: 'ajax_api:request_data',

		/**
		 * The returned data will be passed through this hook, with the endpoint name as hook type and
		 * params.option will have a copy of the original options object.
		 *
		 * Note this hook will be triggered twice if you provide an options.success function.
		 */
		RESPONSE_DATA_HOOK: 'ajax_api:response_data',

		/**
		 * Fetch a value from an Ajax endpoint registered via the "ajaxApi" service
		 *
		 * Note that this function does not support the array form of "success".
		 *
		 * To request the response be cached, set options.data.response_ttl to a number of seconds.
		 *
		 * @param {Object} options including jQuery.ajax options. The default method is "GET".
		 *
		 *     endpoint : {String} required name of the Ajax API endpoint
		 *
		 * @returns {jqXHR}
		 */
		fetch: function (options) {
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
				return elgg.trigger_hook(ajax.RESPONSE_DATA_HOOK, options.endpoint, params, data.data);
			}

			if (!$.isPlainObject(options) || !options.endpoint) {
				throw new Error('options must be a plain with key "endpoint"');
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
			options.data = elgg.trigger_hook(ajax.REQUEST_DATA_HOOK, options.endpoint, params, options.data);

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

			options.url = elgg.get_site_url() + 'ajax/endpoint/' + options.endpoint;

			return $.ajax(options).then(unwrap_data);
		},

		/**
		 * Fetch content and set it as HTML content of $target
		 *
		 * @param {jQuery} $target jQuery-wrapped element(s)
		 * @param {Object} options Fetch options (e.g. data)
		 * @returns {jqXHR}
		 */
		load: function ($target, options) {
			return ajax.fetch(options).done(function (content) {
				$target.html(content);
			});
		},

		/**
		 * Set whether Ajax functions should activate the page-level loading spinner
		 *
		 * @param {Bool} enabled
		 */
		setEnableSpinner: function (enabled) {
			spinner_enabled = !!enabled;
		}
	};

	return ajax;
});
