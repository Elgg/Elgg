/**
 * Avatar cropping
 */

elgg.provide('elgg.avatarCropper');

/**
 * Register the avatar cropper.
 *
 * If the hidden inputs have the coordinates from a previous cropping, begin
 * the selection and preview with that displayed.
 */
elgg.avatarCropper.init = function() {
	var params = {
		selectionOpacity: 0,
		aspectRatio: '1:1',
		onSelectEnd: elgg.avatarCropper.selectChange,
		onSelectChange: elgg.avatarCropper.preview
	};

	if ($('input[name=x2]').val()) {
		params.x1 = $('input[name=x1]').val();
		params.x2 = $('input[name=x2]').val();
		params.y1 = $('input[name=y1]').val();
		params.y2 = $('input[name=y2]').val();
	}

	$('#user-avatar-cropper').imgAreaSelect(params);

	if ($('input[name=x2]').val()) {

		// TODO figure out why this is necessary
		$(window).on('load', function () {
			var ias = $('#user-avatar-cropper').imgAreaSelect({instance: true});
			var selection = ias.getSelection();
			elgg.avatarCropper.preview($('#user-avatar-cropper'), selection);
		});
	}
};

/**
 * Handler for changing select area.
 *
 * @param {Object} img       reference to the image
 * @param {Object} selection imgareaselect selection object
 * @return void
 */
elgg.avatarCropper.preview = function(img, selection) {
	// catch for the first click on the image
	if (selection.width === 0 || selection.height === 0) {
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
};

/**
 * Handler for updating the form inputs after select ends
 *
 * @param {Object} img       reference to the image
 * @param {Object} selection imgareaselect selection object
 * @return void
 */
elgg.avatarCropper.selectChange = function(img, selection) {
	$('input[name=x1]').val(selection.x1);
	$('input[name=x2]').val(selection.x2);
	$('input[name=y1]').val(selection.y1);
	$('input[name=y2]').val(selection.y2);
};

elgg.register_hook_handler('init', 'system', elgg.avatarCropper.init);