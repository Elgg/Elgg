<?php
/**
 * Friend widget options
 *
 */

$num_display = sanitize_int($vars['entity']->num_display, false);
// set default value for display number
if (!$num_display) {
	$num_display = 12;
}

$params = array(
	'name' => 'params[num_display]',
	'value' => $num_display,
	'options' => array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 12, 15, 20, 30, 50, 100),
);
$display_dropdown = elgg_view('input/select', $params);


// handle upgrade to 1.7.2 from previous versions
if ($vars['entity']->icon_size == 1) {
	$vars['entity']->icon_size = 'small';
} elseif ($vars['entity']->icon_size == 2) {
	$vars['entity']->icon_size = 'tiny';
}

// set default value for icon size
if (!isset($vars['entity']->icon_size)) {
	$vars['entity']->icon_size = 'small';
}

$params = array(
	'name' => 'params[icon_size]',
	'value' => $vars['entity']->icon_size,
	'options_values' => array(
		'small' => elgg_echo('friends:small'),
		'tiny' => elgg_echo('friends:tiny'),
	),
);
$size_dropdown = elgg_view('input/select', $params);


?>
<p>
	<?php echo elgg_echo('friends:num_display'); ?>:
	<?php echo $display_dropdown; ?>
</p>

<p>
	<?php echo elgg_echo('friends:icon_size'); ?>:
	<?php echo $size_dropdown; ?>
</p>
