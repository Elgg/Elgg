<?php
/**
 * Edit form for the custom front page
 *
 * @package SitePages
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider Ltd
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.org/
 */

$action = 'sitepages/addfront';

if ($sitepages_object = sitepages_get_sitepage_object('front')) {
	$css = $sitepages_object->title;
	$sitepages_content = $sitepages_object->description;
	$guid = $sitepages_object->guid;
} else {
	$css = '';
	$sitepages_content = '';
	$guid = '';
}

// set the required form variables
$input_css = elgg_view('input/plaintext', array('internalname' => 'css', 'value' => $css));
$input_sitepages_content = elgg_view('input/plaintext', array('internalname' => 'sitepages_content', 'value' => $sitepages_content));
$submit_input = elgg_view('input/submit', array('internalname' => 'submit', 'value' => elgg_echo('save')));

$pageshell_title = elgg_echo("sitepages:front_content");
$css_title = elgg_echo("sitepages:css");

//preview link
// @todo this doesn't do anything.
//$preview = "<div class=\"page_preview\"><a href=\"#preview\">" . elgg_echo('sitepages:preview') . "</a></div>";

//construct the form
$form_body = <<<___EOT

	<h3 class='settings'>$css_title</h3>
	<p class='longtext_editarea'>$input_css</p><br />
	<h3 class='settings'>$pageshell_title</h3>
	<p class='longtext_editarea'>$input_sitepages_content</p>

	$hidden_guid
	<br />
	$submit_input
	$preview

___EOT;

echo elgg_view('input/form', array('action' => "{$vars['url']}action/$action", 'body' => $form_body));