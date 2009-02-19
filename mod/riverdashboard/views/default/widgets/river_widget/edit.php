<?php
	/**
	 * Edit the widget
	 * 
	 * @package ElggRiver
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 */
?>
<p>
	<?php echo elgg_echo('river:widget:label:displaynum'); ?>
	
	<select name="params[num_display]">
		<option value="5" <?php if ($vars['entity']->num_display == 5) echo " selected=\"yes\" "; ?>>5</option>
		<option value="8" <?php if (($vars['entity']->num_display == 8)) echo " selected=\"yes\" "; ?>>8</option>
		<option value="12" <?php if ($vars['entity']->num_display == 12) echo " selected=\"yes\" "; ?>>12</option>
		<option value="15" <?php if ($vars['entity']->num_display == 15) echo " selected=\"yes\" "; ?>>15</option>
	</select>
	
	<?php echo elgg_echo('river:widget:type'); ?>
	
	<select name="params[content_type]">
		<option value="mine" <?php if ($vars['entity']->content_type == 'mine') echo " selected=\"yes\" "; ?>><?php echo elgg_echo("river:widgets:mine");?></option>
		<option value="friends" <?php if (($vars['entity']->content_type != 'mine')) echo " selected=\"yes\" "; ?>><?php echo elgg_echo("river:widgets:friends");?></option>
	</select>
</p>