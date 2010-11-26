<?php
/**
 * Elgg Reported content
 *
 * @package ElggReportedContent
 */

admin_gatekeeper();
set_context('admin');

$title = elgg_view_title(elgg_echo('reportedcontent'));

$reports = elgg_list_entities(array('types' => 'object', 'subtypes' => 'reported_content', 'limit' => 20));

$content = elgg_view("page_elements/contentwrapper", array('body' => $reports));

$body = elgg_view_layout("two_column_left_sidebar", '', $title . $content);

page_draw(elgg_echo('reportedcontent'), $body);
