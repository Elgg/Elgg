import 'jquery';
import elgg from 'elgg';

// hide the current messages
$('.elgg-system-messages .elgg-message').each(function () {
	var $message = $(this);
	
	var delay = $message.data().ttl;
	if (delay === 0) {
		// 0 means an explicit persistent message
		return;
	}
	
	if (!delay && !$message.hasClass('elgg-message-error')) {
		delay = elgg.config.message_delay;
	}
	
	if (!delay) {
		return;
	}
	
	var $list_item = $message.parent();
	$list_item.animate({opacity: '1.0'}, delay).fadeOut('slow');
});

// if the user clicks the before pseudo selector of a system message, make it disappear
$(document).on('click', '.elgg-system-messages .elgg-message', function(e) {
	var $this = $(this);

	// slideUp allows dismissals without notices shifting around unpredictably
	$this.clearQueue().slideUp(100, function () {
		$this.remove();
	});
});

$(document).on('click', '.elgg-system-messages .elgg-message .elgg-inner', function(e) {
	// prevent clicks from bubbling to top so 'closing' a message only works with before pseudo selector content
	e.stopImmediatePropagation();
});

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
	
	// validate delay. Must be a positive integer.
	delay = (typeof delay === 'string') ? parseInt(delay, 10) : delay;
	if (isNaN(delay) || delay < 0) {
		delay = 0;
	}
	
	var messages_html = [];
	msgs.forEach(function(msg) {
		messages_html.push('<li><div class="' + classes.join(' ') + '"><div class="elgg-inner"><div class="elgg-body">' + msg + '</div></div></div></li>');
	});
	
	var $new_messages = $(messages_html.join('')).appendTo($('ul.elgg-system-messages'));
	if (delay > 0) {
		$new_messages.animate({opacity: '1.0'}, delay).fadeOut('slow')
	}
};

export default {
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
