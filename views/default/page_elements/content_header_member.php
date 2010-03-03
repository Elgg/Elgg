<?php
/**
 *	Page Content header when viewing another members entities
	holds the filter menu and any content action buttons
	used on  bookmarks, blog, file, pages,
 **/
$type = $vars['type'];//get the object type 
$user = page_owner_entity();
$user_name = elgg_view_title($user->name . "'s " . elgg_echo($type));
?>
<div id="content_header" class="clearfloat">
	<div class="content_header_title">
		<?php echo $user_name; ?>
	</div>
<!--
	<div class="content_header_options">
		<?php // if we're on bookmarks 		
			if((get_context() == 'bookmarks') && ($user instanceof ElggGroup)) {
				$upload_link = "<a href=\"{$vars['url']}pg/{$type}/{$user->username}/add\" class='action_button'>" . elgg_echo($type . ':new') . "</a>";
				echo $upload_link;
			}
		?>		
	</div>
-->
</div>