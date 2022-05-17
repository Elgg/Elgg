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

/**
 * Add elgg action tokens to an object, URL, or query string (with a ?).
 *
 * @param {FormData|Object|string} data
 * @return {FormData|Object|string} The new data object including action tokens
 * @deprecated
 */
elgg.security.addToken = function (data) {
	var security = require('elgg/security');
	return security.addToken(data);
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
 * @deprecated
 */
elgg.inherit = function(Child, Parent) {
	Child.prototype = new Parent();
	Child.prototype.constructor = Child;
};

/**
 * Create a new ElggEntity
 *
 * @class Represents an ElggEntity
 * @property {number} guid
 * @property {string} type
 * @property {string} subtype
 * @property {number} owner_guid
 * @property {number} container_guid
 * @property {number} time_created
 * @property {number} time_updated
 * @property {string} url
 * @deprecated
 */
elgg.ElggEntity = function(o) {
	$.extend(this, o);
};

/**
 * Create a new ElggUser
 *
 * @param {Object} o
 * @extends ElggEntity
 * @class Represents an ElggUser
 * @property {string} name
 * @property {string} username
 * @property {string} language
 * @property {boolean} admin
 * @deprecated
 */
elgg.ElggUser = function(o) {
	elgg.ElggEntity.call(this, o);
};

elgg.inherit(elgg.ElggUser, elgg.ElggEntity);

/**
 * Is this user an admin?
 *
 * @warning The admin state of the user should be checked on the server for any
 * actions taken that require admin privileges.
 *
 * @return {boolean}
 * @deprecated
 */
elgg.ElggUser.prototype.isAdmin = function() {
	return this.admin;
};

// This just has to happen after ElggUser is defined, however it's probably
// better to have this procedural code here than in ElggUser.js
if (elgg.session.user) {
	elgg.session.user = new elgg.ElggUser(elgg.session.user);
}

/**
 * Returns the object of the user logged in.
 *
 * @return {ElggUser} The logged in user
 * @deprecated
 */
elgg.get_logged_in_user_entity = function() {
	return elgg.session.user;
};

/**
 * @return {number} The GUID of the page owner entity or 0 for no owner
 * @deprecated
 */
elgg.get_page_owner_guid = function() {
	return elgg.page_owner ? elgg.page_owner.guid : 0;
};

/**
 * Analagous to the php version.  Merges translations for a
 * given language into the current translations map.
 * @deprecated
 */
elgg.add_translation = function(lang, translations) {
	var i18n = require('elgg/i18n');
	i18n.addTranslation(lang, translations);
};

/**
 * Get the current language
 * @return {String}
 * @deprecated
 */
elgg.get_language = function() {
	return elgg.config.current_language;
};

/**
 * Translates a string
 *
 * @note The current system only loads a single language module per page, and it comes pre-merged with English
 *       translations. Hence, elgg.echo() can only return translations in the language returned by
 *       elgg.get_language(). Requests for other languages will fail unless a 3rd party plugin has manually
 *       used elgg.add_translation() to merge the language module ahead of time.
 *
 * @param {String} key      Message key
 * @param {Array}  argv     vsprintf() arguments
 * @param {String} language Requested language. Not recommended (see above).
 *
 * @return {String} The translation or the given key if no translation available
 * @deprecated
 */
elgg.echo = function(key, argv, language) {
	var i18n = require('elgg/i18n');
	return i18n.echo(key, argv, language);
};

/**
 * This function registers two menu items that are actions that are the opposite
 * of each other and ajaxifies them. E.g. like/unlike, friend/unfriend, ban/unban, etc.
 *
 * You can also add the data parameter 'data-toggle' to menu items to have them automatically
 * registered as toggleable without the need to call this function.
 * @deprecated
 */
elgg.ui.registerTogglableMenuItems = function(menuItemNameA, menuItemNameB) {
	require(['navigation/menu/elements/item_toggle'], function() {
		menuItemNameA = menuItemNameA.replace('_', '-');
		menuItemNameB = menuItemNameB.replace('_', '-');

		$('.elgg-menu-item-' + menuItemNameA + ' a').not('[data-toggle]').attr('data-toggle', menuItemNameB);
		$('.elgg-menu-item-' + menuItemNameB + ' a').not('[data-toggle]').attr('data-toggle', menuItemNameA);
	});
};
