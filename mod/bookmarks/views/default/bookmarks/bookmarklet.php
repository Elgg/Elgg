<?php
/**
 * Elgg get bookmarks bookmarklet view
 * 
 * @package ElggBookmarks
 */

$page_owner = elgg_get_page_owner_entity();
$bookmarktext = elgg_echo("bookmarks:this");

if ($page_owner instanceof ElggGroup) {
	$bookmarktext = elgg_echo("bookmarks:this:group", array($page_owner->name));
	$name = "group:$page_owner->guid";
} else {
	$name = $page_owner->username;
}

if (!$name && ($user = get_loggedin_user())) {
	$name = $user->username;
}

?>
<h3><?php echo elgg_echo('bookmarks:browser_bookmarklet')?></h3>
<a href="javascript:location.href='<?php echo elgg_get_site_url(); ?>pg/bookmarks/<?php echo $name; ?>/add?address='+encodeURIComponent(location.href)+'&title='+encodeURIComponent(document.title)"> <img src="<?php echo elgg_get_site_url(); ?>_graphics/elgg_bookmarklet.gif" border="0" title="<?php echo elgg_echo('bookmarks:this');?>" /> </a>
<br />
<div class="elgg-discover">
	<a class="link">Instructions</a>
	<div class="elgg-discoverable">
		<p><?php echo elgg_echo("bookmarks:bookmarklet:description"); ?></p>
		<p><?php echo elgg_echo("bookmarks:bookmarklet:descriptionie"); ?></p>
		<p><?php echo elgg_echo("bookmarks:bookmarklet:description:conclusion"); ?></p>
	</div>
</div>