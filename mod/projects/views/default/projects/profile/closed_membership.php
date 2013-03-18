<?php
/**
 * Display message about closed membership
 * 
 * @package ElggGroups
 */

?>
<p class="mtm">
<?php 
echo elgg_echo('projects:closedproject');
if (elgg_is_logged_in()) {
	echo ' ' . elgg_echo('projects:closedproject:request');
}
?>
</p>
