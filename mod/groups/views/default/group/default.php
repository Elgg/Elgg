<?php 
/**
 * Elgg groups profile display
 * 
 * @package ElggGroups
 */

if ($vars['full']) {
	echo elgg_view("groups/groupprofile",$vars);
} else {
	echo elgg_view("groups/grouplisting",$vars);
}
?>