<?php
/**
 * Message on walled, open membership group profile pages when user
 * cannot access group content.
 *
 * @package ElggGroups
 */

?>
<p class="mtm">
<?php
echo elgg_echo('groups:opengroup:walled');
if (elgg_is_logged_in()) {
	echo ' ' . elgg_echo('groups:opengroup:walled:join');
}
?>
</p>