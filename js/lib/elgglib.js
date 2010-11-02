/**
 * 
 * 
 */

/**
 * @namespace Namespace for elgg javascript functions
 */
var elgg = elgg || {};

elgg.assertTypeOf = function(type, param) {
	if (typeof param !== type) {
		throw new TypeError("Expecting param to be a(n) " + type + ".  Was a(n) " + typeof param + ".");
	}
};

/**
 * Pointer to the global context
 * {@see elgg.require} and {@see elgg.provide}
 */
elgg.global = this;

/**
 * Throw an error if the required package isn't present
 * 
 * @param {String} pkg The required package (e.g., 'elgg.package')
 */
elgg.require = function(pkg) {
	elgg.assertTypeOf('string', pkg);
	
	var parts = pkg.split('.'),
		cur = elgg.global,
		part;

	for (var i = 0; i < parts.length; i++) {
		part = parts[i];
		cur = cur[part];
		if(typeof cur == 'undefined') {
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
 * @example elgg.provide('elgg.config.translations')
 * 
 * @param {string} pkg The package name.
 */
elgg.provide = function(pkg) {
	elgg.assertTypeOf('string', pkg);
	
	var parts = pkg.split('.'),
		cur = elgg.global,
		part;
	
	for (var i = 0; i < parts.length; i++) {
		part = parts[i];
		cur[part] = cur[part] || {};
		cur = cur[part];
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
 * @param {Function} childCtor Child class.
 * @param {Function} parentCtor Parent class.
 */
elgg.inherit = function(Child, Parent) {
	Child.prototype = new Parent();
	Child.prototype.constructor = Child;
};

/**
 * Prepend elgg.config.wwwroot to a url if the url doesn't already have it.
 * 
 * @param {String} url The url to extend
 * @return {String} The extended url
 * @private
 */
elgg.normalize_url = function(url) {
	url = url || '';
	elgg.assertTypeOf('string', url);
	
	if(/(^(https?:)?\/\/)/.test(url)) {
		return url;
	}
	
	return elgg.config.wwwroot + url;
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
	if (msgs == undefined) {
		return;
	}
	
	//validate delay.  Must be a positive integer. 
	delay = parseInt(delay);
	if (isNaN(delay) || delay <= 0) {
		delay = 6000;
	}
	
	classes = ['elgg_system_message', 'radius8'];
	if (type == 'error') {
		classes.push('messages_error');
	}

	//Handle non-arrays
	if (msgs.constructor.toString().indexOf("Array") == -1) {
		msgs = [msgs];
	}
	
	var messages_html = [];
	
	for (var i in msgs) {
		messages_html.push('<div class="' + classes.join(' ') + '"><p>' + msgs[i] + '</p></div>');
	}
	
	$(messages_html.join('')).appendTo('#elgg_system_messages').animate({opacity:'1.0'},delay).fadeOut('slow');
};

/**
 * Wrapper function for system_messages. Specifies "messages" as the type of message
 * @param {String} msg The message to display
 * @param {Number} delay How long to display the message (milliseconds)
 */
elgg.system_message = function(msgs, delay) {
	elgg.system_messages(msgs, delay, "message");
};

/**
 * Wrapper function for system_messages.  Specifies "errors" as the type of message
 * @param {String} error The error message to display
 * @param {Number} delay How long to dispaly the error message (milliseconds)
 */
elgg.register_error = function(errors, delay) {
	elgg.system_messages(errors, delay, "error");
};

/**
 * Meant to mimic the php forward() function by simply redirecting the
 * user to another page.
 * 
 * @param {String} url The url to forward to
 */
elgg.forward = function(url) {
	location.href = elgg.normalize_url(url);
};