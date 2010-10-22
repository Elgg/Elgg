<?php
/**
 * Edit non front or SEO site pages.
 *
 * @package Elggsitepages
 *
 */

$page_type = $vars['page_type'];

if ($sitepages_object = sitepages_get_sitepage_object($page_type)) {
		$tags = $sitepages_object->tags;
		$description = $sitepages_object->description;
		$guid = $sitepages_object->getGUID();
} else {
	$tags = array();
	$description = '';
	$guid = '';
}

// set the required form variables
$input_area = elgg_view('input/longtext', array(
	'internalname' => 'sitepages_content',
	'value' => $description
));
$tag_input = elgg_view('input/tags', array(
	'internalname' => 'sitepages_tags',
	'value' => $tags
));

$hidden_value = elgg_view('input/hidden', array(
	'internalname' => 'page_type',
	'value' => $page_type
));

$tag_label = elgg_echo('tags');
$external_page_title = elgg_echo("sitepages:$page_type");

$form_body = <<<___EOT

<p><label>$external_page_title
$input_area</p></label>

<p><label>$tag_label
$tag_input</p></label>

$hidden_value

___EOT;

echo $form_body;
