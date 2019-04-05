/**
 * @namespace Singleton object for holding the Elgg javascript library
 */
var elgg = elgg || {};

/**
 * Pointer to the global context
 *
 * @see elgg.require
 * @see elgg.provide
 */
elgg.global = this;

/**
 * Duplicate of the server side ACCESS_PRIVATE access level.
 *
 * This is a temporary hack to prevent having to mix up js and PHP in js views.
 */
elgg.ACCESS_PRIVATE = 0;

/**
 * Convenience reference to an empty function.
 *
 * Save memory by not generating multiple empty functions.
 */
elgg.nullFunction = function() {};

/**
 * This forces an inheriting class to implement the method or
 * it will throw an error.
 *
 * @example
 * AbstractClass.prototype.toBeImplemented = elgg.abstractMethod;
 */
elgg.abstractMethod = function() {
	throw new Error("Oops... you forgot to implement an abstract method!");
};

/**
 * Merges two or more objects together and returns the result.
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
 */
elgg.isPlainObject = jQuery.isPlainObject;

/**
 * Check if the value is a string
 *
 * @param {*} val
 *
 * @return boolean
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
 */
elgg.isNumber = function(val) {
	return typeof val === 'number';
};

/**
 * Check if the value is an object
 *
 * @note This returns true for functions and arrays!  If you want to return true only
 * for "plain" objects (created using {} or new Object()) use elgg.isPlainObject.
 *
 * @param {*} val
 *
 * @return boolean
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
 */
elgg.isNullOrUndefined = function(val) {
	return val == null;
};

/**
 * Throw an exception of the type doesn't match
 *
 * @todo Might be more appropriate for debug mode only?
 */
elgg.assertTypeOf = function(type, val) {
	if (typeof val !== type) {
		throw new TypeError("Expecting param of " +
							arguments.caller + "to be a(n) " + type + "." +
							"  Was actually a(n) " + typeof val + ".");
	}
};

/**
 * Throw an error if the required package isn't present
 *
 * @param {String} pkg The required package (e.g., 'elgg.package')
 */
elgg.require = function(pkg) {
	elgg.assertTypeOf('string', pkg);

	var parts = pkg.split('.'),
		cur = elgg.global,
		part, i;

	for (i = 0; i < parts.length; i += 1) {
		part = parts[i];
		cur = cur[part];
		if (elgg.isUndefined(cur)) {
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
 */
elgg.provide = function(pkg, opt_context) {
	var parts,
		context = opt_context || elgg.global,
		part, i;

	if (elgg.isArray(pkg)) {
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

/**
 * Inherit the prototype methods from one constructor into another.
 *
 * @example
 * <pre>
 * function ParentClass(a, b) { }
 *
 * ParentClass.prototype.foo = function(a) { alert(a); }
 *
 * function ChildClass(a, b, c) {
 *     //equivalent of parent::__construct(a, b); in PHP
 *     ParentClass.call(this, a, b);
 * }
 *
 * elgg.inherit(ChildClass, ParentClass);
 *
 * var child = new ChildClass('a', 'b', 'see');
 * child.foo('boo!'); // alert('boo!');
 * </pre>
 *
 * @param {Function} Child Child class constructor.
 * @param {Function} Parent Parent class constructor.
 */
elgg.inherit = function(Child, Parent) {
	Child.prototype = new Parent();
	Child.prototype.constructor = Child;
};

/**
 * Converts shorthand urls to absolute urls.
 *
 * If the url is already absolute or protocol-relative, no change is made.
 *
 * elgg.normalize_url('');                   // 'http://my.site.com/'
 * elgg.normalize_url('dashboard');          // 'http://my.site.com/dashboard'
 * elgg.normalize_url('http://google.com/'); // no change
 * elgg.normalize_url('//google.com/');      // no change
 *
 * @param {String} url The url to normalize
 * @return {String} The extended url
 */
elgg.normalize_url = function(url) {
	url = url || '';
	elgg.assertTypeOf('string', url);

	function validate(url) {
		url = elgg.parse_url(url);
		if (url.scheme) {
			url.scheme = url.scheme.toLowerCase();
		}
		if (url.scheme == 'http' || url.scheme == 'https') {
			if (!url.host) {
				return false;
			}
			/* hostname labels may contain only alphanumeric characters, dots and hypens. */
			if (!(new RegExp("^([a-zA-Z0-9][a-zA-Z0-9\\-\\.]*)$", "i")).test(url.host) || url.host.charAt(-1) == '.') {
				return false;
			}
		}
		/* some schemas allow the host to be empty */
		if (!url.scheme || !url.host && url.scheme != 'mailto' && url.scheme != 'news' && url.scheme != 'file') {
			return false;
		}
		return true;
	};

	// ignore anything with a recognized scheme
	if (url.indexOf('http:') === 0 || url.indexOf('https:') === 0 || url.indexOf('javascript:') === 0 || url.indexOf('mailto:') === 0 ) {
		return url;
	} else if (validate(url)) {
		// all normal URLs including mailto:
		return url;
	} else if ((new RegExp("^(\\#|\\?|//)", "i")).test(url)) {
		// '//example.com' (Shortcut for protocol.)
		// '?query=test', #target
		return url;
	} else if ((new RegExp("^[^\/]*\\.php(\\?.*)?$", "i")).test(url)) {
		// watch those double escapes in JS.
		// 'install.php', 'install.php?step=step'
		return elgg.config.wwwroot + url.ltrim('/');
	} else if ((new RegExp("^[^/]*\\.", "i")).test(url)) {
		// 'example.com', 'example.com/subpage'
		return 'http://' + url;
	} else {
		// 'page/handler', 'mod/plugin/file.php'
		// trim off any leading / because the site URL is stored
		// with a trailing /
		return elgg.config.wwwroot + url.ltrim('/');
	}
};

/**
 * Displays system messages via javascript rather than php.
 *
 * @param {String} msgs The message we want to display
 * @param {Number} delay The amount of time to display the message in milliseconds. Defaults to 6 seconds.
 * @param {String} type The type of message (typically 'error' or 'message')
 * @private
 */
elgg.system_messages = function(msgs, delay, type) {
	if (elgg.isUndefined(msgs)) {
		return;
	}

	var classes = ['elgg-message'],
		messages_html = [],
		appendMessage = function(msg) {
			messages_html.push('<li><div class="' + classes.join(' ') + '"><div class="elgg-inner"><div class="elgg-body">' + msg + '</div></div></div></li>');
		},
		systemMessages = $('ul.elgg-system-messages'),
		i;

	//validate delay.  Must be a positive integer.
	delay = parseInt(delay || 6000, 10);
	if (isNaN(delay) || delay <= 0) {
		delay = 6000;
	}

	//Handle non-arrays
	if (!elgg.isArray(msgs)) {
		msgs = [msgs];
	}

	if (type === 'error') {
		classes.push('elgg-message-error');
	} else {
		classes.push('elgg-message-success');
	}

	msgs.forEach(appendMessage);

	if (type != 'error') {
		$(messages_html.join('')).appendTo(systemMessages)
			.animate({opacity: '1.0'}, delay).fadeOut('slow');
	} else {
		$(messages_html.join('')).appendTo(systemMessages);
	}
};

/**
 * Helper function to remove all current system messages
 */
elgg.clear_system_messages = function() {
	$('ul.elgg-system-messages').empty();
};

/**
 * Wrapper function for system_messages. Specifies "messages" as the type of message
 * @param {String} msgs  The message to display
 * @param {Number} delay How long to display the message (milliseconds)
 */
elgg.system_message = function(msgs, delay) {
	elgg.system_messages(msgs, delay, "message");
};

/**
 * Wrapper function for system_messages.  Specifies "errors" as the type of message
 * @param {String} errors The error message to display
 * @param {Number} delay  How long to dispaly the error message (milliseconds)
 */
elgg.register_error = function(errors, delay) {
	elgg.system_messages(errors, delay, "error");
};

/**
 * Informs admin users via a console message about use of a deprecated function or capability
 *
 * @param {String} msg         The deprecation message to display
 * @param {String} dep_version The version the function was deprecated for
 * @since 1.9
 */
elgg.deprecated_notice = function(msg, dep_version) {
	if (elgg.is_admin_logged_in()) {
		msg = "Deprecated in Elgg " + dep_version + ": " + msg;
		if (typeof console !== "undefined") {
			console.info(msg);
		}
	}
};

/**
 * Meant to mimic the php forward() function by simply redirecting the
 * user to another page.
 *
 * @param {String} url The url to forward to
 */
elgg.forward = function(url) {
	var dest = elgg.normalize_url(url);

	if (dest == location.href) {
		location.reload();
	}

	// in case the href set below just changes the hash, we want to reload. There's sadly
	// no way to force a reload and set a different hash at the same time.
	$(window).on('hashchange', function () {
		location.reload();
	});

	location.href = dest;
};

/**
 * Parse a URL into its parts. Mimicks http://php.net/parse_url
 *
 * @param {String}  url       The URL to parse
 * @param {Number}  component A component to return
 * @param {Boolean} expand    Expand the query into an object? Else it's a string.
 *
 * @return {Object} The parsed URL
 */
elgg.parse_url = function(url, component, expand) {
	// Adapted from http://blog.stevenlevithan.com/archives/parseuri
	// which was release under the MIT
	// It was modified to fix mailto: and javascript: support.
	expand = expand || false;
	component = component || false;
	
	var re_str =
		// scheme (and user@ testing)
		'^(?:(?![^:@]+:[^:@/]*@)([^:/?#.]+):)?(?://)?'
		// possibly a user[:password]@
		+ '((?:(([^:@]*)(?::([^:@]*))?)?@)?'
		// host and port
		+ '([^:/?#]*)(?::(\\d*))?)'
		// path
		+ '(((/(?:[^?#](?![^?#/]*\\.[^?#/.]+(?:[?#]|$)))*/?)?([^?#/]*))'
		// query string
		+ '(?:\\?([^#]*))?'
		// fragment
		+ '(?:#(.*))?)';
	var keys = {
		1: "scheme",
		4: "user",
		5: "pass",
		6: "host",
		7: "port",
		9: "path",
		12: "query",
		13: "fragment"
	};
	var results = {};

	if (url.indexOf('mailto:') === 0) {
		results['scheme'] = 'mailto';
		results['path'] = url.replace('mailto:', '');
		return results;
	}

	if (url.indexOf('javascript:') === 0) {
		results['scheme'] = 'javascript';
		results['path'] = url.replace('javascript:', '');
		return results;
	}

	var re = new RegExp(re_str);
	var matches = re.exec(url);

	for (var i in keys) {
		if (matches[i]) {
			results[keys[i]] = matches[i];
		}
	}

	if (expand && typeof(results['query']) != 'undefined') {
		results['query'] = elgg.parse_str(results['query']);
	}

	if (component) {
		if (typeof(results[component]) != 'undefined') {
			return results[component];
		} else {
			return false;
		}
	}
	return results;
};

/**
 * Returns an object with key/values of the parsed query string.
 *
 * @param  {String} string The string to parse
 * @return {Object} The parsed object string
 */
elgg.parse_str = function(string) {
	var params = {},
		result,
		key,
		value,
		re = /([^&=]+)=?([^&]*)/g,
		re2 = /\[\]$/;

	// assignment intentional
	while (result = re.exec(string)) {
		key = decodeURIComponent(result[1].replace(/\+/g, ' '));
		value = decodeURIComponent(result[2].replace(/\+/g, ' '));

		if (re2.test(key)) {
			key = key.replace(re2, '');
			if (!params[key]) {
				params[key] = [];
			}
			params[key].push(value);
		} else {
			params[key] = value;
		}
	}
	
	return params;
};

/**
 * Returns a jQuery selector from a URL's fragement.  Defaults to expecting an ID.
 *
 * Examples:
 *  http://elgg.org/download.php returns ''
 *	http://elgg.org/download.php#id returns #id
 *	http://elgg.org/download.php#.class-name return .class-name
 *	http://elgg.org/download.php#a.class-name return a.class-name
 *
 * @param {String} url The URL
 * @return {String} The selector
 */
elgg.getSelectorFromUrlFragment = function(url) {
	var fragment = url.split('#')[1];

	if (fragment) {
		// this is a .class or a tag.class
		if (fragment.indexOf('.') > -1) {
			return fragment;
		} else {
			// this is an id
			return '#' + fragment;
		}
	}
	return '';
};

/**
 * Adds child to object[parent] array.
 *
 * @param {Object} object The object to add to
 * @param {String} parent The parent array to add to.
 * @param {*}      value  The value
 */
elgg.push_to_object_array = function(object, parent, value) {
	elgg.assertTypeOf('object', object);
	elgg.assertTypeOf('string', parent);

	if (!(object[parent] instanceof Array)) {
		object[parent] = [];
	}

	if ($.inArray(value, object[parent]) < 0) {
		return object[parent].push(value);
	}

	return false;
};

/**
 * Tests if object[parent] contains child
 *
 * @param {Object} object The object to add to
 * @param {String} parent The parent array to add to.
 * @param {*}      value  The value
 */
elgg.is_in_object_array = function(object, parent, value) {
	elgg.assertTypeOf('object', object);
	elgg.assertTypeOf('string', parent);

	return typeof(object[parent]) != 'undefined' && $.inArray(value, object[parent]) >= 0;
};
