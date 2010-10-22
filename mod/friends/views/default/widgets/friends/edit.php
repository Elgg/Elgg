<?php

/**
 * Elgg Friends
 * Friend widget options
 *
 * @package ElggFriends
 * @subpackage Core
 */

$selections = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 12, 15, 20, 30, 50, 100);
$icon_sizes = array('small', 'tiny');

// set defaults
if (!isset($vars['entity']->num_display)) {
	$vars['entity']->num_display = 12;
	$vars['entity']->icon_size = 'small';
}

// handle upgrade to 1.7.2 from previous versions
if ($vars['entity']->icon_size == 1) {
	$vars['entity']->icon_size = 'small';
} elseif ($vars['entity']->icon_size == 2) {
	$vars['entity']->icon_size = 'tiny';
}
?>

<p>
	<?php echo elgg_echo("friends:num_display"); ?>:
	<select name="params[num_display]">
<?php
foreach ($selections as $selection) {
	$selected = '';
	if ($vars['entity']->num_display == $selection) {
		$selected = 'selected="selected"';
	}
	echo "<option value=\"$selection\" $selected>$selection</option>";
}
?>
	</select>
</p>

<p>
	<?php echo elgg_echo("friends:icon_size"); ?>
	<select name="params[icon_size]">
<?php
foreach ($icon_sizes as $size) {
	$selected = '';
	if ($vars['entity']->icon_size == $size) {
		$selected = 'selected="selected"';
	}
	$label = elgg_echo("friends:$size");
	echo "<option value=\"$size\" $selected>$label</option>";
}
?>
	</select>
</p>
