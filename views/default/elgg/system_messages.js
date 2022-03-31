define(['jquery', 'elgg'], function ($, elgg) {

	// hide the current messages (not on admin pages)
	$('.elgg-page-default .elgg-system-messages .elgg-message-success').parent().animate({opacity: '1.0'}, elgg.config.message_delay).fadeOut('slow');
	
	/**
	 * Displays system messages via javascript rather than php.
	 *
	 * @param {String} msgs The message we want to display
	 * @param {Number} delay The amount of time to display the message in milliseconds. Defaults to global config delay seconds.
	 * @param {String} type The type of message (typically 'error' or 'success')
	 */
	function showMessage(msgs, delay, type) {
		if (msgs === undefined) {
			return;
		}
		
		//Handle non-arrays
		if (!Array.isArray(msgs)) {
			msgs = [msgs];
		}
		
		var classes = ['elgg-message'];
		if (type) {
			classes.push('elgg-message-' + type);
		}
		
		//validate delay.  Must be a positive integer.
		delay = parseInt(delay, 10);
		if (isNaN(delay) || delay < 0) {
			delay = 0;
		}
		
		var messages_html = [];
		msgs.forEach(function(msg) {
			messages_html.push('<li><div class="' + classes.join(' ') + '"><div class="elgg-inner"><div class="elgg-body">' + msg + '</div></div></div></li>');
		});
		
		$new_messages = $(messages_html.join('')).appendTo($('ul.elgg-system-messages'));
		if (delay > 0) {
			$new_messages.animate({opacity: '1.0'}, delay).fadeOut('slow')
		}
	};

	return {
		/**
		 * Helper function to remove all current system messages
		 */
		clear: function () {
			$('ul.elgg-system-messages').empty();
		},
		
		/**
		 * Wrapper function for showMessage. Specifies "success" as the type of message
		 *
		 * @param {String} message The message to display
		 * @param {Number} delay   How long to display the message (milliseconds)
		 */
		success: function (message, delay) {
			delay = parseInt(delay || elgg.config.message_delay, 10);
			
			showMessage(message, delay, 'success');
		},
		
		/**
		 * Wrapper function for showMessage.  Specifies "errors" as the type of message
		 *
		 * @param {String} message The error message to display
		 * @param {Number} delay   How long to dispaly the error message (milliseconds)
		 */
		error: function (message, delay) {
			showMessage(message, delay, 'error');
		}
	};
});
