<?php
/**
 * Elgg Reported content admin page
 *
 * @package ElggReportedContent
 */

$list = elgg_list_entities(array('type' => 'object', 'subtype' => 'reported_content'));
if (!$list) {
	$list = '<p class="mtm">' . elgg_echo('reportedcontent:none') . '</p>';
}

echo $list;
