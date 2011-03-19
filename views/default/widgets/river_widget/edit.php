<?php
/**
 * Edit settings for river widget
 */

// dashboard widget has type parameter
if (elgg_in_context('dashboard')) {
	if (!isset($vars['entity']->content_type)) {
		$vars['entity']->content_type = 'friends';
	}
	$params = array(
		'name' => 'params[content_type]',
		'value' => $vars['entity']->content_type,
		'options_values' => array(
			'friends' => elgg_echo('river:widgets:friends'),
			'all' => elgg_echo('river:widgets:all'),
		),
	);
	$type_dropdown = elgg_view('input/dropdown', $params);
	?>
	<div>
		<?php echo elgg_echo('river:widget:type'); ?>:
		<?php echo $type_dropdown; ?>
	</div>
	<?php
}


// set default value for number to display
if (!isset($vars['entity']->num_display)) {
	$vars['entity']->num_display = 8;
}

$params = array(
	'name' => 'params[num_display]',
	'value' => $vars['entity']->num_display,
	'options' => array(5, 8, 10, 12, 15, 20),
);
$num_dropdown = elgg_view('input/dropdown', $params);

?>
<div>
	<?php echo elgg_echo('widget:numbertodisplay'); ?>:
	<?php echo $num_dropdown; ?>
</div>
