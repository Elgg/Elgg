elgg.provide('elgg.thewire');

elgg.thewire.init = function() {
	$("#thewire-textarea").live('keydown', function() {
		elgg.thewire.textCounter(this, $("#thewire-characters-remaining span"), 140);
	});
	$("#thewire-textarea").live('keyup', function() {
		elgg.thewire.textCounter(this, $("#thewire-characters-remaining span"), 140);
	});
}

elgg.thewire.textCounter = function(textarea, status, limit) {

	var remaining_chars = limit - textarea.value.length;
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

elgg.register_hook_handler('init', 'system', elgg.thewire.init);