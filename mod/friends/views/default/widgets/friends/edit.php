<?php

    /**
	 * Elgg Friends
	 * Friend widget options
	 * 
	 * @package ElggFriends
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.org/
	 */
	 
?>

<p>
		<?php echo elgg_echo("friends:num_display"); ?>:
		<select name="params[num_display]">
			<option value="1" <?php if($vars['entity']->num_display == 1) echo "SELECTED"; ?>>1</option>
			<option value="2" <?php if($vars['entity']->num_display == 2) echo "SELECTED"; ?>>2</option>
			<option value="3" <?php if($vars['entity']->num_display == 3) echo "SELECTED"; ?>>3</option>
			<option value="4" <?php if($vars['entity']->num_display == 4) echo "SELECTED"; ?>>4</option>
			<option value="5" <?php if($vars['entity']->num_display == 5) echo "SELECTED"; ?>>5</option>
			<option value="6" <?php if($vars['entity']->num_display == 6) echo "SELECTED"; ?>>6</option>
			<option value="7" <?php if($vars['entity']->num_display == 7) echo "SELECTED"; ?>>7</option>
			<option value="8" <?php if($vars['entity']->num_display == 8) echo "SELECTED"; ?>>8</option>
			<option value="9" <?php if($vars['entity']->num_display == 9) echo "SELECTED"; ?>>9</option>
			<option value="10" <?php if($vars['entity']->num_display == 10) echo "SELECTED"; ?>>10</option>
			<option value="15" <?php if($vars['entity']->num_display == 15) echo "SELECTED"; ?>>15</option>
			<option value="20" <?php if($vars['entity']->num_display == 20) echo "SELECTED"; ?>>20</option>
			<option value="30" <?php if($vars['entity']->num_display == 30) echo "SELECTED"; ?>>30</option>
			<option value="50" <?php if($vars['entity']->num_display == 50) echo "SELECTED"; ?>>50</option>
			<option value="100" <?php if($vars['entity']->num_display == 100) echo "SELECTED"; ?>>100</option>
		</select>
</p>

<p>
    <?php echo elgg_echo("friends:icon_size"); ?>
    <select name="params[icon_size]">
        <option value="1" <?php if($vars['entity']->icon_size == 1) echo "SELECTED"; ?>><?php echo elgg_echo("friends:small"); ?></option>
	    <option value="2" <?php if($vars['entity']->icon_size == 2) echo "SELECTED"; ?>><?php echo elgg_echo("friends:tiny"); ?></option>
    </select>
</p>