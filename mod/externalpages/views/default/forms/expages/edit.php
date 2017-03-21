<?php
/**
 * Edit form body for external pages
 *
 * @uses $vars['type']
 *
 */

$type = $vars['type'];

//grab the required entity
$page_contents = elgg_get_entities([
	'type' => 'object',
	'subtype' => $type,
	'limit' => 1,
]);

if ($page_contents) {
	$description = $page_contents[0]->description;
	$guid = $page_contents[0]->guid;
} else {
	$description = "";
	$guid = 0;
}

// set the required form variables
$input_area = elgg_view('input/longtext', [
	'name' => 'expagescontent',
	'value' => $description,
]);
$submit_input = elgg_view('input/submit', [
	'name' => 'submit',
	'value' => elgg_echo('save'),
]);
$view_page = elgg_view('output/url', [
	'text' => elgg_echo('expages:edit:viewpage'),
	'href' => $type,
	'target' => '_blank',
	'class' => 'elgg-button elgg-button-action float-alt',
]);
$hidden_type = elgg_view('input/hidden', [
	'name' => 'content_type',
	'value' => $type,
]);
$hidden_guid = elgg_view('input/hidden', [
	'name' => 'guid',
	'value' => $guid,
]);

$external_page_title = elgg_echo("expages:$type");

//construct the form
echo <<<EOT
<div class="mtm">
	<label>$external_page_title</label>
	$input_area
</div>
<div class="elgg-foot">
$hidden_guid
$hidden_type
$view_page
$submit_input
</div>
EOT;

