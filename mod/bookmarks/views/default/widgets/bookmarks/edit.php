<?php
/**
 * Elgg bookmark widget edit view
 * 
 * @package ElggBookmarks
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider <info@elgg.com>
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.org/
 */

?>
<p>
	<?php echo elgg_echo('bookmarks:numbertodisplay'); ?>:
	<select name="params[num_display]">
<?php

for ($i=1; $i<=10; $i++) {
	$selected = '';
	if ($vars['entity']->num_display == $i) {
		$selected = "selected='selected'";
	}

	echo "	<option value='{$i}' $selected >{$i}</option>\n";
}
?>
	</select>
</p>