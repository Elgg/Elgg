<?php

/**
 * List most recent bookmarks on group profile page
 */

if ($vars['entity']->bookmarks_enable != 'no') {
?>

<div class="group_widget">
<h2><?php echo elgg_echo('bookmarks:group'); ?></h2>
<?php
	$context = get_context();
	set_context('search');
	$content = elgg_list_entities(array('types' => 'object',
										'subtypes' => 'bookmarks',
										'container_guid' => $vars['entity']->guid,
										'limit' => 5,
										'full_view' => FALSE,
										'pagination' => FALSE));
	set_context($context);

    if ($content) {
		echo $content;

		$more_url = "{$vars['url']}pg/bookmarks/owner/group:{$vars['entity']->guid}/";
		echo "<div class=\"forum_latest\"><a href=\"$more_url\">" . elgg_echo('bookmarks:more') . "</a></div>";
	} else {
		echo "<div class=\"forum_latest\">" . elgg_echo("bookmarks:nogroup") . "</div>";
	}
?>
</div>
<?php
}