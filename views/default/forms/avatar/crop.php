<?php
/**
 * Avatar crop form
 *
 * @uses $vars['entity']
 */

$master_image = $vars['entity']->getIcon('master');

?>
<div>
	<img id="user-avatar" class="mrl" src="<?php echo $master_image; ?>" alt="<?php echo elgg_echo('avatar'); ?>" />
</div>

<div class="clearfloat"></div>
<?php
$coords = array('x1', 'x2', 'y1', 'y2');
foreach ($coords as $coord) {
	echo elgg_view('input/hidden', array('name' => $coord, 'value' => $vars['entity']->$coord));
}

echo elgg_view('input/hidden', array('name' => 'guid', 'value' => $vars['entity']->guid));

echo elgg_view('input/submit', array('value' => elgg_echo('avatar:create')));

?>
<!-- grab the required js for icon cropping -->
<script type="text/javascript" src="<?php echo elgg_get_site_url(); ?>vendors/jquery/jquery.imgareaselect-0.8.min.js"></script>
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

		// this produces the coordinates
		$('#user-avatar').imgAreaSelect({ selectionOpacity: 0, onSelectEnd: selectChange });
		// show the preview
		$('#user-avatar').imgAreaSelect({ aspectRatio: '1:1', onSelectChange: preview });
	});
</script>
