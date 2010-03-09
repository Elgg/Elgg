<?php
/**
 * Edit non front or SEO site pages.
 *
 * @package Elggsitepages
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider Ltd <info@elgg.com>
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.com/
 *
 */

$page_type = $vars['page_type'];
$action = 'sitepages/add';

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
$input_area = elgg_view('input/longtext', array('internalname' => 'sitepages_content', 'value' => $description));
$tag_input = elgg_view('input/tags', array('internalname' => 'sitepages_tags', 'value' => $tags));

$submit_input = elgg_view('input/submit', array('internalname' => 'submit', 'value' => elgg_echo('save')));
$hidden_value = elgg_view('input/hidden', array('internalname' => 'page_type', 'value' => $page_type));

$tag_label = '<h3>' . elgg_echo('tags') . '</h3>';
$external_page_title = elgg_echo("sitepages:$page_type");

$form_body = <<<___EOT

<h3 class='settings'>$external_page_title</h3>
<p class='longtext_editarea'>$input_area</p>
<p>
	$tag_label
	$tag_input
</p>
$hidden_value
$hidden_guid
<br />
$submit_input

___EOT;

echo elgg_view('input/form', array('action' => "{$vars['url']}action/$action", 'body' => $form_body));