<?php
/**
 * Elgg Reported content admin page
 *
 * @package ElggReportedContent
 */

$title = elgg_view_title(elgg_echo('reportedcontent'));

$list = elgg_list_entities(array('types' => 'object', 'subtypes' => 'reported_content'));
if (!$list) {
	$list = '<p class="margin-top">' . elgg_echo('reportedcontent:none') . '</p>';
}

$body = <<<__HTML
$title
$list
__HTML;

echo $body;