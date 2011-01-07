<?php
/**
 * Display message about closed membership
 * 
 * @package ElggGroups
 */

?>
<p class="mtm">
<?php 
echo elgg_echo('groups:closedgroup');
if (isloggedin()) {
	echo ' ' . elgg_echo('groups:closedgroup:request');
}
?>
</p>
