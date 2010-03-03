<?php
/**
 * bookmarklet tool
 **/
$page_owner = page_owner_entity();
?>
<h3>Browser Bookmarklet</h3>
<a href="javascript:location.href='<?php echo $vars['url']; ?>pg/bookmarks/<?php echo $page_owner->username; ?>/add?address='+encodeURIComponent(location.href)+'&title='+encodeURIComponent(document.title)"> <img src="<?php echo $vars['url']; ?>_graphics/elgg_bookmarklet.gif" border="0" title="<?php echo elgg_echo('bookmarks:this');?>" /> </a>
<br />
<a href="#" onclick="elgg_slide_toggle(this,'#elgg_sidebar','.bookmarklet');">Instructions</a>

<div class="bookmarklet hidden">
	<p>
		<?php echo elgg_echo("bookmarks:bookmarklet:description"); ?>
	</p>
	<p>
		<?php echo elgg_echo("bookmarks:bookmarklet:descriptionie"); ?>
	</p>

	<p>
		<?php echo elgg_echo("bookmarks:bookmarklet:description:conclusion"); ?>
	</p>		
</div>