<?php
/**
 * Pages sidebar
 */

echo elgg_view('page/elements/comments_block', array(
	'subtypes' => array('page', 'page_top'),
	'owner_guid' => elgg_get_page_owner_guid(),
));
