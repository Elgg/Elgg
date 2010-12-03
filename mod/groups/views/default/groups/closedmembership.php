<?php
	/**
	 * Elgg groups plugin full profile view (for a closed group you haven't joined).
	 * 
	 * @package ElggGroups
	 */

?>
<p class="margin-top">
<?php 
echo elgg_echo('groups:closedgroup');
if (isloggedin()) {
	echo ' ' . elgg_echo('groups:closedgroup:request');
}
?>
</p>
