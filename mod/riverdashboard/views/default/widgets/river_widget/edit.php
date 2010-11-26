<?php
/**
 * Edit the widget
 *
 * @package ElggRiverDash
 */

if (!$vars['entity']->content_type) {
	$content_type = 'mine';
} else {
	$content_type = $vars['entity']->content_type;
}

?>
<p>
	<?php echo elgg_echo('river:widget:label:displaynum'); ?>

	<select name="params[num_display]">
		<option value="5" <?php if ($vars['entity']->num_display == 5) echo " selected=\"yes\" "; ?>>5</option>
		<option value="8" <?php if (($vars['entity']->num_display == 8)) echo " selected=\"yes\" "; ?>>8</option>
		<option value="12" <?php if ($vars['entity']->num_display == 12) echo " selected=\"yes\" "; ?>>12</option>
		<option value="15" <?php if ($vars['entity']->num_display == 15) echo " selected=\"yes\" "; ?>>15</option>
	</select>
</p>
<p>	
	<?php echo elgg_echo('river:widget:type'); ?>

	<select name="params[content_type]">
		<option value="mine" <?php if ($content_type == 'mine') echo " selected=\"yes\" "; ?>><?php echo elgg_echo("river:widgets:mine");?></option>
		<option value="friends" <?php if ($content_type != 'mine') echo " selected=\"yes\" "; ?>><?php echo elgg_echo("river:widgets:friends");?></option>
	</select>
</p>