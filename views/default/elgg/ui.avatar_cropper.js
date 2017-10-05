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
	
	var $img = $('#user-avatar-cropper');
	var img = $img.get(0);
	
	var params = {
		selectionOpacity: 0,
		aspectRatio: '1:1',
		onSelectEnd: elgg.avatarCropper.selectChange,
		onSelectChange: elgg.avatarCropper.preview,
		imageHeight: img.naturalHeight,
		imageWidth: img.naturalWidth
	};

	if ($('input[name=x2]').val()) {
		params.x1 = $('input[name=x1]').val();
		params.x2 = $('input[name=x2]').val();
		params.y1 = $('input[name=y1]').val();
		params.y2 = $('input[name=y2]').val();
	}

	$img.imgAreaSelect(params);
};

/**
 * Handler for updating the form inputs after select ends
 *
 * @param {Object} img       reference to the image
 * @param {Object} selection imgareaselect selection object
 * @return void
 */
elgg.avatarCropper.selectChange = function(img, selection) {
	
	// Elgg expects a square, because of rounding issues this can be a difference of 1px
	var rounding_diff = selection.width - selection.height;
	
	$('input[name=x1]').val(selection.x1);
	$('input[name=x2]').val(selection.x2);
	$('input[name=y1]').val(selection.y1);
	$('input[name=y2]').val(selection.y2 + rounding_diff);
};

elgg.register_hook_handler('init', 'system', elgg.avatarCropper.init);
