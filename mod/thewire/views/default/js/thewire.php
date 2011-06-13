<?php
/**
 * The wire's JavaScript
 */

$site_url = elgg_get_site_url();

?>

elgg.provide('elgg.thewire');

elgg.thewire.init = function() {
	$("#thewire-textarea").live('keydown', function() {
		elgg.thewire.textCounter(this, $("#thewire-characters-remaining span"), 140);
	});
	$("#thewire-textarea").live('keyup', function() {
		elgg.thewire.textCounter(this, $("#thewire-characters-remaining span"), 140);
	});

	$(".thewire-previous").live('click', elgg.thewire.viewPrevious);
}

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
		status.parent().css("color", "#D40D12");
		$("#thewire-submit-button").attr('disabled', 'disabled');
		$("#thewire-submit-button").addClass('elgg-state-disabled');
	} else {
		status.parent().css("color", "");
		$("#thewire-submit-button").removeAttr('disabled', 'disabled');
		$("#thewire-submit-button").removeClass('elgg-state-disabled');
	}
}

/**
 * Display the previous wire post
 *
 * Makes Ajax call to load the html and handles changing the previous link
 *
 * @param {Object} event
 * @return void
 */
elgg.thewire.viewPrevious = function(event) {
	var $link = $(this);
	var postGuid = $link.attr("href").split("/").pop();
	var $previousDiv = $("#thewire-previous-" + postGuid);

	if ($link.html() == "<?php echo elgg_echo('thewire:hide'); ?>") {
		$link.html("<?php echo elgg_echo('thewire:previous'); ?>");
		$link.attr("title", "<?php echo elgg_echo('thewire:previous:help'); ?>");
		$previousDiv.slideUp(400);
	} else {
		$link.html("<?php echo elgg_echo('thewire:hide'); ?>");
		$link.attr("title", "<?php echo elgg_echo('thewire:hide:help'); ?>");
		
		$.ajax({type: "GET",
			url: "<?php echo $site_url . "ajax/view/thewire/previous"; ?>",
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

	event.preventDefault();
}

elgg.register_hook_handler('init', 'system', elgg.thewire.init);
