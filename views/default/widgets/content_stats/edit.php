<?php
/**
 * Content statistics widget edit view
 */

$num_display = sanitize_int($vars['entity']->num_display, false);
// set default value for display number
if (!$num_display) {
	$num_display = 8;
}

$params = array(
	'name' => 'params[num_display]',
	'value' => $num_display,
	'options' => array(5, 8, 10, 12, 15, 20),
);
$dropdown = elgg_view('input/select', $params);

?>
<p>
	<?php echo elgg_echo('widget:numbertodisplay'); ?>:
	<?php echo $dropdown; ?>
</p>
