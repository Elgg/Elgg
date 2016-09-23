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
	$type_dropdown = elgg_view('input/select', $params);
	?>
	<div>
		<?php echo elgg_echo('river:widget:type'); ?>:
		<?php echo $type_dropdown; ?>
	</div>
	<?php
}

echo elgg_view('object/widget/edit/num_display', [
	'entity' => elgg_extract('entity', $vars),
	'default' => 8,
]);

// pass the context so we have the correct output upon save.
if (elgg_in_context('dashboard')) {
	$context = 'dashboard';
} else {
	$context = 'profile';
}

echo elgg_view('input/hidden', array(
	'name' => 'context',
	'value' => $context
));