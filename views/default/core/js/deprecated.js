/**
 * Pointer to the global context
 *
 * @see elgg.require
 * @see elgg.provide
 * 
 * @deprecated
 */
elgg.global = this;

/**
 * Duplicate of the server side ACCESS_PRIVATE access level.
 *
 * This is a temporary hack to prevent having to mix up js and PHP in js views.
 * 
 * @deprecated
 */
elgg.ACCESS_PRIVATE = 0;

/**
 * Convenience reference to an empty function.
 *
 * Save memory by not generating multiple empty functions.
 * 
 * @deprecated
 */
elgg.nullFunction = function() {};

/**
 * This forces an inheriting class to implement the method or
 * it will throw an error.
 *
 * @example
 * AbstractClass.prototype.toBeImplemented = elgg.abstractMethod;
 * 
 * @deprecated
 */
elgg.abstractMethod = function() {
	throw new Error("Oops... you forgot to implement an abstract method!");
};

/**
 * Merges two or more objects together and returns the result.
 * 
 * @deprecated
 */
elgg.extend = jQuery.extend;

/**
 * Check if the value is an array.
 *
 * No sense in reinventing the wheel!
 *
 * @param {*} val
 *
 * @return boolean
 * 
 * @deprecated
 */
elgg.isArray = jQuery.isArray;

/**
 * Check if the value is a function.
 *
 * No sense in reinventing the wheel!
 *
 * @param {*} val
 *
 * @return boolean
 * 
 * @deprecated
 */
elgg.isFunction = jQuery.isFunction;

/**
 * Check if the value is a "plain" object (i.e., created by {} or new Object())
 *
 * No sense in reinventing the wheel!
 *
 * @param {*} val
 *
 * @return boolean
 * 
 * @deprecated
 */
elgg.isPlainObject = jQuery.isPlainObject;

/**
 * Check if the value is a string
 *
 * @param {*} val
 *
 * @return boolean
 * 
 * @deprecated
 */
elgg.isString = function(val) {
	return typeof val === 'string';
};

/**
 * Check if the value is a number
 *
 * @param {*} val
 *
 * @return boolean
 * 
 * @deprecated
 */
elgg.isNumber = function(val) {
	return typeof val === 'number';
};

/**
 * Check if the value is an object
 *
 * @note This returns true for functions and arrays!  If you want to return true only
 * for "plain" objects (created using {} or new Object()) use $.isPlainObject.
 *
 * @param {*} val
 *
 * @return boolean
 * 
 * @deprecated
 */
elgg.isObject = function(val) {
	return typeof val === 'object';
};

/**
 * Check if the value is undefined
 *
 * @param {*} val
 *
 * @return boolean
 * 
 * @deprecated
 */
elgg.isUndefined = function(val) {
	return val === undefined;
};

/**
 * Check if the value is null
 *
 * @param {*} val
 *
 * @return boolean
 * 
 * @deprecated
 */
elgg.isNull = function(val) {
	return val === null;
};

/**
 * Check if the value is either null or undefined
 *
 * @param {*} val
 *
 * @return boolean
 * 
 * @deprecated
 */
elgg.isNullOrUndefined = function(val) {
	return val == null;
};

/**
 * Throw an error if the required package isn't present
 *
 * @param {String} pkg The required package (e.g., 'elgg.package')
 * 
 * @deprecated
 */
elgg.require = function(pkg) {
	elgg.assertTypeOf('string', pkg);

	var parts = pkg.split('.'),
		cur = elgg.global,
		part, i;

	for (i = 0; i < parts.length; i += 1) {
		part = parts[i];
		cur = cur[part];
		if (cur === undefined) {
			throw new Error("Missing package: " + pkg);
		}
	}
};

/**
 * Generate the skeleton for a package.
 *
 * <pre>
 * elgg.provide('elgg.package.subpackage');
 * </pre>
 *
 * is equivalent to
 *
 * <pre>
 * elgg = elgg || {};
 * elgg.package = elgg.package || {};
 * elgg.package.subpackage = elgg.package.subpackage || {};
 * </pre>
 *
 * An array package name can be given if any subpackage names need to contain a period.
 *
 * <pre>
 * elgg.provide(['one', 'two.three']);
 * </pre>
 *
 * is equivalent to
 *
 * one = one || {};
 * one['two.three'] = one['two.three'] || {};
 *
 * @example elgg.provide('elgg.config.translations')
 *
 * @param {String|Array} pkg The package name. Only use an array if a subpackage name needs to contain a period.
 *
 * @param {Object} opt_context The object to extend (defaults to this)
 * 
 * @deprecated
 */
elgg.provide = function(pkg, opt_context) {
	var parts,
		context = opt_context || elgg.global,
		part, i;

	if (Array.isArray(pkg)) {
		parts = pkg;
	} else {
		elgg.assertTypeOf('string', pkg);
		parts = pkg.split('.');
	}

	for (i = 0; i < parts.length; i += 1) {
		part = parts[i];
		context[part] = context[part] || {};
		context = context[part];
	}
};

// register provides for backwards compatibility
elgg.provide('elgg.config');
elgg.provide('elgg.session');
elgg.provide('elgg.ui');
elgg.provide('elgg.security.token');
elgg.provide('elgg.config.translations');
elgg.provide('elgg.config.hooks');
elgg.provide('elgg.config.instant_hooks');
elgg.provide('elgg.config.triggered_hooks');

/**
 * Helper function for setting cookies
 * @param {string} name
 * @param {string} value
 * @param {Object} options
 *
 * {number|Date} options[expires]
 * {string} options[path]
 * {string} options[domain]
 * {boolean} options[secure]
 *
 * @return {string|undefined} The value of the cookie, if only name is specified. Undefined if no value set
 * 
 * @deprecated
 */
elgg.session.cookie = function(name, value, options) {
	var cookies = [], cookie = [], i = 0, date, valid = true;
	
	//elgg.session.cookie()
	if (name === undefined) {
		return document.cookie;
	}
	
	//elgg.session.cookie(name)
	if (value === undefined) {
		if (document.cookie && document.cookie !== '') {
			cookies = document.cookie.split(';');
			for (i = 0; i < cookies.length; i += 1) {
				cookie = jQuery.trim(cookies[i]).split('=');
				if (cookie[0] === name) {
					return decodeURIComponent(cookie[1]);
				}
			}
		}
		return undefined;
	}
	
	// elgg.session.cookie(name, value[, opts])
	options = options || {};
	
	if (value === null) {
		value = '';
		options.expires = -1;
	}
	
	cookies.push(name + '=' + value);

	if (options.expires) {
		if (typeof options.expires === 'number') {
			date = new Date();
			date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
		} else if (options.expires.toUTCString) {
			date = options.expires;
		}

		if (date) {
			cookies.push('expires=' + date.toUTCString());
		}
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
 * Displays system messages via javascript rather than php.
 *
 * @param {String} msgs The message we want to display
 * @param {Number} delay The amount of time to display the message in milliseconds. Defaults to 6 seconds.
 * @param {String} type The type of message (typically 'error' or 'message')
 * @private
 * @deprecated
 */
elgg.system_messages = function(msgs, delay, type) {
	require(['elgg/system_messages'], function(messages) {
		messages.showMessage(msgs, delay, type);
	});
};

/**
 * Helper function to remove all current system messages
 * @deprecated
 */
elgg.clear_system_messages = function() {
	require(['elgg/system_messages'], function(messages) {
		messages.clear();
	});
};

/**
 * Wrapper function for system_messages. Specifies "success" as the type of message
 * @param {String} msgs  The message to display
 * @param {Number} delay How long to display the message (milliseconds)
 * @deprecated
 */
elgg.system_message = function(msgs, delay) {
	require(['elgg/system_messages'], function(messages) {
		messages.success(msgs, delay);
	});
};

/**
 * Wrapper function for system_messages.  Specifies "errors" as the type of message
 * @param {String} errors The error message to display
 * @param {Number} delay  How long to dispaly the error message (milliseconds)
 * @deprecated
 */
elgg.register_error = function(errors, delay) {
	require(['elgg/system_messages'], function(messages) {
		messages.error(errors, delay);
	});
};
