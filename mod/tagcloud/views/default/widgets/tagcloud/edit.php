<?php
/**
 * Tagcloud widget edit view
 *
 */

// set default value
if (!isset($vars['entity']->num_items)) {
	$vars['entity']->num_items = 30;
}

$params = array(
	'name' => 'params[num_items]',
	'value' => $vars['entity']->num_items,
	'options' => array(10, 20, 30, 50, 100),
);
$dropdown = elgg_view('input/dropdown', $params);

?>
<p>
	<?php echo elgg_echo('tagcloud:widget:numtags'); ?>:
	<?php echo $dropdown; ?>
</p>
