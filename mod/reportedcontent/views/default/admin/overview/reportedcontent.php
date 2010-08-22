<?php
/**
 * Elgg Reported content
 * 
 * @package ElggReportedContent
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider Ltd
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.com/
 */

$title = elgg_view_title(elgg_echo('reportedcontent'));

$reported = elgg_get_entities(array('types' => 'object', 'subtypes' => 'reported_content', 'limit' => 9999));
$list = elgg_view("reportedcontent/listing", array('entity' => $reported));
	
// Display main admin menu
$body = <<<__HTML
$title
$reported
$list
__HTML;

echo $body;