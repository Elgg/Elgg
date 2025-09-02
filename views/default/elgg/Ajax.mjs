import 'jquery';
import elgg from 'elgg';
import spinner from 'elgg/spinner';
import system_messages from 'elgg/system_messages';
import security from 'elgg/security';
import i18n from 'elgg/i18n';
import hooks from 'elgg/hooks';

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

	use_spinner = (use_spinner == null) ? true : !!use_spinner;

	var that = this;
	var spinner_starts = 0;

	/**
	 * Fetch a value from an Ajax endpoint.
	 *
	 * @param {Object} options   See {@link jQuery#ajax}. The default method is "GET" (or "POST" for actions).
	 *
	 *     data: {Object} Data to send to the server (optional). If set to a string (e.g. $.serialize)
	 *                    then the request hook will not be called.
	 *
	 *           elgg_response_ttl:  {Number} Sets response max-age. To be effective, you must also
	 *                                        set option.method to "GET".
	 *
	 *           elgg_fetch_message: {Number} Set to 0 to bypass downloading server messages
	 *
	 * @param {String} hook_type Type of the plugin hooks. If missing, the hooks will not trigger.
	 *
	 * @returns {jqXHR}
	 */
	function fetch(options, hook_type) {
		var orig_options,
			params,
			jqXHR,
			metadata_extracted = false,
			error_displayed = false;

		/**
		 * Show messages and require dependencies
		 *
		 * @param {Object} data
		 * @param {Number} status_code HTTP status code
		 */
		function extract_metadata(data, status_code) {

			status_code = status_code || 200;

			if (!metadata_extracted) {
				const m = data._elgg_msgs;
				if (m && m.error) {
					data.error = m.error;
				}

				if (data.error && options.showErrorMessages) {
					system_messages.error(data.error);
					error_displayed = true;
				}

				if (data.error || status_code !== 200) {
					data.status = -1;
				} else {
					data.status = 0;
				}

				m && m.success && options.showSuccessMessages && system_messages.success(m.success);
				delete data._elgg_msgs;

				const deps = data._elgg_deps;
				if (deps && deps.js) {
					deps.js.forEach((dep) => import(dep));
				}
				
				if (deps && deps.css) {
					deps.css.forEach(function (dep) {
						if ($('link[rel="stylesheet"][data-name="' + dep.name + '"]').length) {
							return;
						}
						
						let link = document.createElement('link');
						link.type = 'text/css';
						link.rel = 'stylesheet';
						
						link.href = dep.href;
						link.setAttribute('data-name', dep.name);
						if (dep.integrity) {
							link.integrity = dep.integrity;
							link.crossorigin = 'anonymous';
						}
						
						document.head.appendChild(link);
					});
				}
				
				delete data._elgg_deps;

				metadata_extracted = true;
			}
		}

		/**
		 * For unit testing
		 * @type {{options: Object, hook_type: String}}
		 * @private
		 */
		that._fetch_args = {
			options: options,
			hook_type: hook_type
		};

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
		
		if (options.showSuccessMessages === undefined) {
			options.showSuccessMessages = true;
		}
		
		if (options.showErrorMessages === undefined) {
			options.showErrorMessages = true;
		}
		
		if ($.isPlainObject(options.data)) {
			options.data = options.data || {};
		} else if (options.data instanceof FormData) {
			options.processData = false;
			options.contentType = false;
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
			options.data = hooks.trigger(Ajax.REQUEST_DATA_HOOK, hook_type, params, options.data);
		}

		// we do this here because hook may have made data non-empty, in which case we'd need to
		// default to POST
		if (!options.method) {
			options.method = 'GET';
			if (options.data && !$.isEmptyObject(options.data)) {
				options.method = 'POST';
			}
		}

		if (use_spinner) {
			options.beforeSend = function () {
				orig_options.beforeSend && orig_options.beforeSend.apply(null, arguments);
				spinner_starts++;
				spinner.start();
			};
			options.complete = function () {
				spinner_starts--;
				if (spinner_starts < 1) {
					spinner.stop();
				}
				
				orig_options.complete && orig_options.complete.apply(null, arguments);
			};
		}

		var custom_error = function() {};
		if (options.error) {
			custom_error = options.error;
		}
		
		options.error = function (jqXHR, textStatus, errorThrown) {
			if (!jqXHR.getAllResponseHeaders()) {
				// trigger custom error
				custom_error(jqXHR, textStatus, errorThrown);
				
				// user aborts (like refresh or navigate) do not have headers
				return;
			}

			try {
				var data = $.parseJSON(jqXHR.responseText);
				if ($.isPlainObject(data)) {
					extract_metadata(data, jqXHR.status);
				}
			} catch (e) {
				if (window.console) {
					console.warn(e.message);
				}
			}

			if (!error_displayed && options.showErrorMessages) {
				system_messages.error(i18n.echo('ajax:error'));
			}
			
			// trigger custom error
			custom_error(jqXHR, textStatus, errorThrown);
		};

		options.dataFilter = function (data, type) {
			if (type !== 'json') {
				return data;
			}

			data = $.parseJSON(data);

			extract_metadata(data, 200);

			var params = {
				options: orig_options
			};
			if (hook_type) {
				data = hooks.trigger(Ajax.RESPONSE_DATA_HOOK, hook_type, params, data);
			}

			jqXHR.AjaxData = data;

			if (data.value !== undefined) {
				// regular JSON responses wrap the 'data' in 'value'
				return JSON.stringify(data.value);
			}
			
			return JSON.stringify(data);
		};

		options.url = elgg.normalize_url(options.url);
		options.headers = {
			'X-Elgg-Ajax-API': '2'
		};

		/**
		 * For unit testing
		 * @type {Object}
		 * @private
		 */
		that._ajax_options = options;

		jqXHR = $.ajax(options);

		return jqXHR;
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
			path = path.substring(site_url.length);
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
		options.method = options.method || 'GET';

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
		options.method = options.method || 'GET';

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
			action = action.substring(action_base.length);
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
			options.data = security.addToken(options.data);
		}

		options.method = options.method || 'POST';
		options.url = 'action/' + action;

		// /foo/?arg=1 => foo
		action = action.replace(query_pattern, '').replace(slashes_pattern, '');

		return fetch(options, 'action:' + action);
	};

	/**
	 * Convert a form element to a FormData object.
	 *
	 * @param {*} el HTML form element or CSS selector (to a form element)
	 * @returns {FormData}
	 */
	this.objectify = function (el) {
		
		/*
		 * Triggering an event to allow preparation of the form to happen.
		 * Plugins like CKEditor can use this to populate the fields with actual values.
		 */
		$(el).trigger('elgg-ajax-objectify');
		
		return new FormData($(el)[0]);
	};

	/**
	 * Issue a redirect and display a spinner
	 *
	 * @param destination String URL to forward to
	 * @returns {void}
	 */
	this.forward = function(destination) {
		spinner_starts++;
		spinner.start();
		elgg.forward(destination);
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

export default Ajax;
