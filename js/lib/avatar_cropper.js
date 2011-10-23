/**
 * Avatar cropping
 */

elgg.provide('elgg.avatarCropper');

/**
 * Register the avatar cropper.
 */
elgg.avatarCropper.init = function() {
	$('#user-avatar-cropper').imgAreaSelect({
		selectionOpacity: 0,
		aspectRatio: '1:1',
		onSelectEnd: elgg.avatarCropper.selectChange,
		onSelectChange: elgg.avatarCropper.preview
	});
}

/**
 * Handler for changing select area.
 */
elgg.avatarCropper.preview = function(img, selection) {
	// catch for the first click on the image
	if (selection.width == 0 || selection.height == 0) {
		return;
	}

	var origWidth = $("#user-avatar-cropper").width();
	var origHeight = $("#user-avatar-cropper").height();
	var scaleX = 100 / selection.width;
	var scaleY = 100 / selection.height;
	$('#user-avatar-preview > img').css({
		width: Math.round(scaleX * origWidth) + 'px',
		height: Math.round(scaleY * origHeight) + 'px',
		marginLeft: '-' + Math.round(scaleX * selection.x1) + 'px',
		marginTop: '-' + Math.round(scaleY * selection.y1) + 'px'
	});
}

/**
 * Handler for updating the form inputs after select ends
 */
elgg.avatarCropper.selectChange = function(img, selection) {
	$('input[name=x1]').val(selection.x1);
	$('input[name=x2]').val(selection.x2);
	$('input[name=y1]').val(selection.y1);
	$('input[name=y2]').val(selection.y2);
}

elgg.register_hook_handler('init', 'system', elgg.avatarCropper.init);