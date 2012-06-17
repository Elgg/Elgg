<?php
/**
 * Message on walled, closed membership group profile pages when user
 * cannot access group content.
 *
 * @package ElggGroups
 */

?>
<p class="mtm">
<?php
echo elgg_echo('groups:closedgroup:walled');
if (elgg_is_logged_in()) {
	echo ' ' . elgg_echo('groups:closedgroup:request');
}
?>
</p>