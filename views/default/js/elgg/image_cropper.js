/**
 * Image cropper
 */
define(function(require) {
	var elgg = require('elgg');
	var $ = require('jquery');

	/**
	 * Register the image cropper
	 *
	 * If the hidden inputs have the coordinates from a previous
	 * cropping, begin the selection and preview with that displayed.
	 */
	function init () {
		var params = {
			selectionOpacity: 0,
			aspectRatio: '1:1',
			onSelectEnd: selectChange,
			onSelectChange: preview
		};

		if ($('input[name=x2]').val()) {
			params.x1 = $('input[name=x1]').val();
			params.x2 = $('input[name=x2]').val();
			params.y1 = $('input[name=y1]').val();
			params.y2 = $('input[name=y2]').val();
		}

		$('#entity-image-cropper').imgAreaSelect(params);

		if ($('input[name=x2]').val()) {
			var ias = $('#entity-image-cropper').imgAreaSelect({instance: true});
			var selection = ias.getSelection();
			preview($('#entity-image-cropper'), selection);
		}
	};

	/**
	 * Handler for changing select area
	 *
	 * @param {Object} reference to the image
	 * @param {Object} imgareaselect selection object
	 * @return void
	 */
	function preview (img, selection) {
		// catch for the first click on the image
		if (selection.width === 0 || selection.height === 0) {
			return;
		}

		var origWidth = $("#entity-image-cropper").width();
		var origHeight = $("#entity-image-cropper").height();
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
	 * @param {Object} reference to the image
	 * @param {Object} imgareaselect selection object
	 * @return void
	 */
	function selectChange (img, selection) {
		$('input[name=x1]').val(selection.x1);
		$('input[name=x2]').val(selection.x2);
		$('input[name=y1]').val(selection.y1);
		$('input[name=y2]').val(selection.y2);
	};

	init();
});
