<?php
/**
 * Bookmarklet
 *
 * @package Bookmarks
 */

$page_owner = elgg_get_page_owner_entity();

if ($page_owner instanceof ElggGroup) {
	$title = elgg_echo("bookmarks:this:group", array($page_owner->name));
} else {
	$title = elgg_echo("bookmarks:this");
}

$guid = $page_owner->getGUID();

if (!$name && ($user = elgg_get_logged_in_user_entity())) {
	$name = $user->username;
}

$url = elgg_get_site_url();

$bookmarklet = "<a href=\"javascript:location.href='{$url}bookmarks/add/$guid?address='"
	. "+encodeURIComponent(location.href)+'&title='+encodeURIComponent(document.title)\">"
	. "<img src=\"{$url}mod/bookmarks/graphics/bookmarklet.gif\" alt=\"$title\" /> </a>";

?>
<p><?php echo elgg_echo("bookmarks:bookmarklet:description"); ?></p>
<p><?php echo $bookmarklet; ?></p>
<p><?php echo elgg_echo("bookmarks:bookmarklet:descriptionie"); ?></p>
<p><?php echo elgg_echo("bookmarks:bookmarklet:description:conclusion"); ?></p>