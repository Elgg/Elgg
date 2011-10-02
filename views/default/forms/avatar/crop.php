<?php
/**
 * Avatar crop form
 *
 * @uses $vars['entity']
 */

elgg_load_js('jquery.imgareaselect');
elgg_load_css('jquery.imgareaselect');

$master_image = $vars['entity']->getIconUrl('master');

?>
<div class="clearfix">
	<img id="user-avatar" class="mrl" src="<?php echo $master_image; ?>" alt="<?php echo elgg_echo('avatar'); ?>" />
</div>
<div class="elgg-foot">
<?php
$coords = array('x1', 'x2', 'y1', 'y2');
foreach ($coords as $coord) {
	echo elgg_view('input/hidden', array('name' => $coord, 'value' => $vars['entity']->$coord));
}

echo elgg_view('input/hidden', array('name' => 'guid', 'value' => $vars['entity']->guid));

echo elgg_view('input/submit', array('value' => elgg_echo('avatar:create')));

?>
</div>
<!-- grab the required js for icon cropping -->
<?php //@todo JS 1.8: no ?>
<script type="text/javascript">

	// display a preview of the users cropped section
	function preview(img, selection) {
		// catch for the first click on the image
		if (selection.width == 0 || selection.height == 0) {
			return;
		}

		var origWidth = $("#user-avatar").width(); //get the width of the users master photo
		var origHeight = $("#user-avatar").height(); //get the height of the users master photo
		var scaleX = 100 / selection.width;
		var scaleY = 100 / selection.height;
		$('#user-avatar-preview > img').css({
			width: Math.round(scaleX * origWidth) + 'px',
			height: Math.round(scaleY * origHeight) + 'px',
			marginLeft: '-' + Math.round(scaleX * selection.x1) + 'px',
			marginTop: '-' + Math.round(scaleY * selection.y1) + 'px'
		});
	}

	function selectChange(img, selection) {
		// populate the form with the correct coordinates once a user has cropped their image
		$('input[name=x1]').val(selection.x1);
		$('input[name=x2]').val(selection.x2);
		$('input[name=y1]').val(selection.y1);
		$('input[name=y2]').val(selection.y2);
	}

	$(document).ready(function() {
		$('<div id="user-avatar-preview"><img src="<?php echo $master_image; ?>" /></div>').insertAfter($('#user-avatar'));
		$('<div id="user-avatar-preview-title"><label><?php echo elgg_echo('avatar:preview'); ?></label></div>').insertBefore($('#user-avatar-preview'));

		// init the cropping
		$('#user-avatar').imgAreaSelect({
			selectionOpacity: 0,
			aspectRatio: '1:1',
			onSelectEnd: selectChange,
			onSelectChange: preview
		});
	});
</script>
