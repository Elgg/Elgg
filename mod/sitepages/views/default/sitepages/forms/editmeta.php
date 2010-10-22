<?php
/**
 * Edit form for the custom meta tags and desc
 *
 * @package SitePages
 */

if ($sitepages_object = sitepages_get_sitepage_object('seo')) {
	$meta_tags = $sitepages_object->title;
	$meta_description = $sitepages_object->description;
} else {
	$meta_tags = '';
	$meta_description = '';
}

$description = elgg_echo('sitepages:metadescription');
$input_description = elgg_view('input/plaintext', array(
	'internalname' => 'description',
	'value' => $meta_description
));

$metatags = elgg_echo('sitepages:metatags');
$input_keywords = elgg_view('input/text', array(
	'internalname' => 'metatags',
	'value' => $meta_tags
));

$page_type = elgg_view('input/hidden', array(
	'internalname' => 'page_type',
	'value' => 'seo',
));

$form_body = <<<___EOT
<p><label>$description $input_description</p></label>
<p><label>$metatags $input_keywords</p></label>
$page_type
___EOT;

//display the form
echo $form_body;