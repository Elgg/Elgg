/**
 * The wire's JavaScript
 */
elgg.provide('elgg.thewire');

elgg.thewire.init = function() {
	var callback = function() {
		var maxLength = $(this).data('max-length');
		if (maxLength) {
			elgg.thewire.textCounter(this, $("#thewire-characters-remaining span"), maxLength);
		}
	};

	$(document).on('input propertychange', "#thewire-textarea", callback);
	$(document).on('click', ".thewire-previous", elgg.thewire.viewPrevious);
};

/**
 * Update the number of characters left with every keystroke
 *
 * @param {Object}  textarea
 * @param {Object}  status
 * @param {integer} limit
 * @return void
 */
elgg.thewire.textCounter = function(textarea, status, limit) {

	var remaining_chars = limit - $(textarea).val().length;
	status.html(remaining_chars);

	if (remaining_chars < 0) {
		status.parent().addClass("thewire-characters-remaining-warning");
		$("#thewire-submit-button").attr('disabled', 'disabled');
		$("#thewire-submit-button").addClass('elgg-state-disabled');
	} else {
		status.parent().removeClass("thewire-characters-remaining-warning");
		$("#thewire-submit-button").removeAttr('disabled', 'disabled');
		$("#thewire-submit-button").removeClass('elgg-state-disabled');
	}
};

/**
 * Display the previous wire post
 *
 * Makes Ajax call to load the html and handles changing the previous link
 *
 * @param {Object} event
 * @return void
 */
elgg.thewire.viewPrevious = function(event) {
	event.preventDefault();

	var $link = $(this);
	var postGuid = $link.attr("href").split("/").pop();
	var $previousDiv = $("#thewire-previous-" + postGuid);

	require([
		'elgg/echo!hide',
		'elgg/echo!previous',
		'elgg/echo!thewire:previous:help',
		'elgg/echo!thewire:hide:help'
	], function (hide, previous, previous_help, hide_help) {
		if ($link.html() == hide()) {
			$link.html(previous());
			$link.attr("title", previous_help());
			$previousDiv.slideUp(400);
		} else {
			$link.html(hide());
			$link.attr("title", hide_help());

			elgg.get({
				url: elgg.config.wwwroot + "ajax/view/thewire/previous",
				dataType: "html",
				cache: false,
				data: {guid: postGuid},
				success: function(htmlData) {
					if (htmlData.length > 0) {
						$previousDiv.html(htmlData);
						$previousDiv.slideDown(600);
					}
				}
			});

		}
	});
};

elgg.register_hook_handler('init', 'system', elgg.thewire.init);
