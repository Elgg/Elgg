<?php
/**
 * Message for non-members on closed membership group profile pages.
 *
 * @package ElggGroups
 */

?>
<p class="mtm">
<?php
echo elgg_echo('groups:closedgroup');
if (elgg_is_logged_in()) {
	echo ' ' . elgg_echo('groups:closedgroup:request');
}
?>
</p>
