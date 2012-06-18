<?php
/**
 * Message on members only, open membership group profile pages when user
 * cannot access group content.
 *
 * @package ElggGroups
 */

?>
<p class="mtm">
<?php
echo elgg_echo('groups:opengroup:membersonly');
if (elgg_is_logged_in()) {
	echo ' ' . elgg_echo('groups:opengroup:membersonly:join');
}
?>
</p>