<?php
/**
 * Elgg Reported content
 *
 * @package ElggReportedContent
 */

$title = elgg_view_title(elgg_echo('reportedcontent'));

$reported = elgg_get_entities(array('types' => 'object', 'subtypes' => 'reported_content', 'limit' => 9999));
$list = elgg_view("reportedcontent/listing", array('entity' => $reported));

// Display main admin menu
$body = <<<__HTML
$title
$list
__HTML;

echo $body;