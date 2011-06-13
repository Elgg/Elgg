<?php
/**
 * Edit form body for external pages
 * 
 * @uses $vars['type']
 * 
 */

$type = $vars['type'];

//grab the required entity
$page_contents = elgg_get_entities(array(
	'type' => 'object',
	'subtype' => $type,
	'limit' => 1,
));

if ($page_contents) {
	$description = $page_contents[0]->description;
	$guid = $page_contents[0]->guid;
} else {
	$description = "";
	$guid = 0;
}

// set the required form variables
$input_area = elgg_view('input/longtext', array(
	'name' => 'expagescontent',
	'value' => $description,
));
$submit_input = elgg_view('input/submit', array(
	'name' => 'submit',
	'value' => elgg_echo('save'),
));
$hidden_type = elgg_view('input/hidden', array(
	'name' => 'content_type',
	'value' => $type,
));
$hidden_guid = elgg_view('input/hidden', array(
	'name' => 'guid',
	'value' => $guid,
));

$external_page_title = elgg_echo("expages:$type");

//construct the form
echo <<<EOT
<div class="mtm">
	<label>$external_page_title</label>
	$input_area
</div>
$hidden_value
$hidden_type
$submit_input

EOT;

