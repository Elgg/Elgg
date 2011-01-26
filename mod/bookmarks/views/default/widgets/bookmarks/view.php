<?php

/**
 * Elgg bookmark widget view
 *
 * @package ElggBookmarks
 */
//get the num of shares the user want to display
$num = $vars['entity']->num_display;

$options = array(
	'type' => 'object',
	'subtype' => 'bookmarks',
	'container_guid' => $vars['entity']->owner_guid,
	'limit' => $num,
);
$bookmarks = elgg_get_entities($options);

$options['count'] = true;
$num_bookmarks = elgg_get_entities($options);


if ($bookmarks) {

	foreach ($bookmarks as $b) {

		//get the owner
		$owner = $b->getOwnerEntity();

		//get the time
		$friendlytime = elgg_view_friendly_time($b->time_created);

		//get the bookmark title
		$info = "<div class='river_object_bookmarks_create'><p class=\"shares_title\"><a href=\"{$b->address}\">{$b->title}</a></p></div>";

		//get the user details
		$info .= "<p class=\"shares_timestamp\"><small>{$friendlytime} ";

		//get the bookmark description
		if ($b->description) {
			$info .= "<a href=\"javascript:void(0);\" class=\"share_more_info\">" . elgg_echo('bookmarks:more') . "</a></small></p><div class=\"share_desc\"><p>{$b->description}</p></div>";
		} else {
			$info .= "</small></p>";
		}

		//display
		echo "<div class='ContentWrapper bookmarks'>";
		echo "<div class='shares_widget-content'>" . $info . "</div></div>";
	}

	if ($num_bookmarks > $num) {
		$user_inbox = elgg_get_site_url() . "pg/bookmarks/" . elgg_get_page_owner_entity()->username;
		echo "<div class='ContentWrapper bookmarks more'><a href=\"{$user_inbox}\">" . elgg_echo('bookmarks:read') . "</a></div>";
	}
} else {
	echo "<div class='ContentWrapper'>" . elgg_echo("bookmarks:widget:description") . "</div>";
}
