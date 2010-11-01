/**
 * @author Evan Winslow
 * 
 * $Id: elgglib.js 76 2010-07-17 02:08:02Z evan.b.winslow $
 */

/**
 * @namespace Namespace for elgg javascript functions
 */
var elgg = elgg || {};

elgg.init = function() {
	//if the user clicks a system message, make it disappear
	$('.elgg_system_message').live('click', function() {
		$(this).stop().fadeOut('fast');
	});
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
 */
elgg.provide = function(pkg) {
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
 * Prepend elgg.config.wwwroot to a url if the url doesn't already have it.
 * 
 * @param {String} url The url to extend
 * @return {String} The extended url
 * @private
 */
elgg.extendUrl = function(url) {
	url = url || '';
	if(url.indexOf(elgg.config.wwwroot) == -1) {
		url = elgg.config.wwwroot + url;
	}
	
	return url;
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
	
	var messages_class = 'messages';
	if (type == 'error') {
		messages_class = 'messages_error';
	}

	//Handle non-arrays
	if (msgs.constructor.toString().indexOf("Array") == -1) {
		msgs = [msgs];
	}
	
	var messages_html = '<div class="' + messages_class + '">' 
		+ '<span class="closeMessages">'
			+ '<a href="#">' 
				+ elgg.echo('systemmessages:dismiss')
			+ '</a>'
		+ '</span>'
		+ '<p>' + msgs.join('</p><p>') + '</p>'
	+ '</div>';
	
	$(messages_html).insertAfter('#layout_header').click(function () {
		$(this).stop().fadeOut('slow');
		return false;
	}).show().animate({opacity:'1.0'},delay).fadeOut('slow');
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
	location.href = elgg.extendUrl(url);
};

/**
 * Initialise Elgg
 * @todo How should plugins, etc. initialize themselves?
 */
$(function() {
	elgg.init();
});
