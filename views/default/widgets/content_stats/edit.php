<?php
/**
 * Content statistics widget edit view
 */


// set default value
if (!isset($vars['entity']->num_display)) {
	$vars['entity']->num_display = 8;
}

$params = array(
	'name' => 'params[num_display]',
	'value' => $vars['entity']->num_display,
	'options' => array(5, 8, 10, 12, 15, 20),
);
$dropdown = elgg_view('input/dropdown', $params);

?>
<p>
	<?php echo elgg_echo('widget:numbertodisplay'); ?>:
	<?php echo $dropdown; ?>
</p>
