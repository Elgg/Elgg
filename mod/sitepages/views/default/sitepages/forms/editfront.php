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
	$css = $sitepages_object->css;
	$logged_in_content = $sitepages_object->logged_in_content;
	$logged_out_content = $sitepages_object->logged_out_content;
} else {
	$css = '';
	$logged_in_content = '';
	$logged_out_content = '';
}

// set the required form variables
$input_css = elgg_view('input/plaintext', array('internalname' => 'css', 'value' => $css));
$input_logged_in_content = elgg_view('input/plaintext', array('internalname' => 'logged_in_content', 'value' => $logged_in_content ));
$input_logged_out_content = elgg_view('input/plaintext', array('internalname' => 'logged_out_content', 'value' => $logged_out_content ));
$submit_input = elgg_view('input/submit', array('internalname' => 'submit', 'value' => elgg_echo('save')));

$logged_in_content_title = elgg_echo("sitepages:logged_in_front_content");
$logged_out_content_title = elgg_echo("sitepages:logged_out_front_content");
$css_title = elgg_echo("sitepages:css");

//preview link
// @todo this doesn't do anything.
//$preview = "<div class=\"page_preview\"><a href=\"#preview\">" . elgg_echo('sitepages:preview') . "</a></div>";

//construct the form
$form_body = <<<___EOT

	<h3 class='settings'>$css_title</h3>
	<p class='longtext_editarea'>$input_css</p><br />


	<h3 class='settings'>$logged_in_content_title</h3>
	<p class='longtext_editarea'>$input_logged_in_content</p><br />

	<h3 class='settings'>$logged_out_content_title</h3>
	<p class='longtext_editarea'>$input_logged_out_content</p>

	$hidden_guid
	<br />
	$submit_input
	$preview

___EOT;

echo elgg_view('input/form', array('action' => "{$vars['url']}action/$action", 'body' => $form_body));