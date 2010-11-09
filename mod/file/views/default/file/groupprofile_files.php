<?php

/**
 * List most recent files on group profile page
 */

if ($vars['entity']->file_enable != 'no') {
?>

<div class="group_widget">
<h2><?php echo elgg_echo('file:group'); ?></h2>
<?php
	$context = get_context();
	set_context('search');
	$content = elgg_list_entities(array('types' => 'object',
										'subtypes' => 'file',
										'container_guid' => $vars['entity']->guid,
										'limit' => 5,
										'full_view' => FALSE,
										'pagination' => FALSE));
	set_context($context);

    if ($content) {
		echo $content;

		$more_url = "{$vars['url']}pg/file/owner/group:{$vars['entity']->guid}/";
		echo "<div class=\"forum_latest\"><a href=\"$more_url\">" . elgg_echo('file:more') . "</a></div>";
	} else {
		echo "<div class=\"forum_latest\">" . elgg_echo("file:nogroup") . "</div>";
	}
?>
</div>
<?php
}