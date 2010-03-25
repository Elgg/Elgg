<?php
/**
 * Edit form for the custom meta tags and desc
 *
 * @package SitePages
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider Ltd
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.org/
 */

$action = 'sitepages/addmeta';

if ($sitepages_object = sitepages_get_sitepage_object('seo')) {
	$meta_tags = $sitepages_object->title;
	$meta_description = $sitepages_object->description;
} else {
	$meta_tags = '';
	$meta_description = '';
}

$input_keywords = elgg_view('input/text', array('internalname' => 'metatags', 'value' => $meta_tags));
$input_description = elgg_view('input/plaintext', array('internalname' => 'description', 'value' => $meta_description));
$submit_input = elgg_view('input/submit', array('internalname' => 'submit', 'value' => elgg_echo('save')));

$description = elgg_echo("sitepages:metadescription");
$metatags = elgg_echo("sitepages:metatags");

$form_body = <<<___EOT

<p><label>$description
$input_description</p></label>

<p><label>$metatags
$input_keywords</p></label>

$hidden_guid
$submit_input

___EOT;

//display the form
echo elgg_view('input/form', array('action' => "{$vars['url']}action/$action", 'body' => $form_body));