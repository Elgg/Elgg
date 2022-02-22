/**
 * Displays system messages via javascript rather than php.
 *
 * @param {String} msgs The message we want to display
 * @param {Number} delay The amount of time to display the message in milliseconds. Defaults to 6 seconds.
 * @param {String} type The type of message (typically 'error' or 'message')
 * @private
 */
elgg.system_messages = function(msgs, delay, type) {
	if (msgs === undefined) {
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
	if (!Array.isArray(msgs)) {
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
