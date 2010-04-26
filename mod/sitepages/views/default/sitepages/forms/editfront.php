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
	$css = <<<___EOT
#elgg_sidebar .entity_listing_info {width:173px;}
___EOT;
	$logged_in_content = <<<___EOT
<div id="elgg_content" class="clearfloat sidebar">
	<div id="elgg_sidebar">
	<h3>Newest members:</h3>[userlist list_type=new only_with_avatars=TRUE limit=5]
</div>

<div id="elgg_page_contents" class="clearfloat">
	<h2>All site activity</h2>
	[activity]
	</div>
</div>
___EOT;
	$logged_out_content = <<<___EOT
<div id="elgg_content" class="clearfloat sidebar">
	<div id="elgg_sidebar">
	[loginbox]
	<h3>Newest members:</h3>[userlist: list_type=new, only_with_avatars=TRUE, limit=5]
</div>

<div id="elgg_page_contents" class="clearfloat">
	<h2>Welcome to [networkname]</h2><p class="margin_top">Introduction and instructions might go here. Learn about this network, and how to get registered and start adding content.</p>
	<h2>All site activity</h2>
	[activity]
	</div>
</div>
___EOT;
}

// set the required form variables
$input_css = elgg_view('input/plaintext', array('internalname' => 'css', 'value' => $css, 'class' => 'input_textarea monospace'));
$input_logged_in_content = elgg_view('input/plaintext', array('internalname' => 'logged_in_content', 'value' => $logged_in_content, 'class' => 'input_textarea monospace'));
$input_logged_out_content = elgg_view('input/plaintext', array('internalname' => 'logged_out_content', 'value' => $logged_out_content, 'class' => 'input_textarea monospace'));
$submit_input = elgg_view('input/submit', array('internalname' => 'submit', 'value' => elgg_echo('save')));

$logged_in_content_title = elgg_echo("sitepages:logged_in_front_content");
$logged_out_content_title = elgg_echo("sitepages:logged_out_front_content");
$css_title = elgg_echo("sitepages:css");

//preview link
// @todo this doesn't do anything.
//$preview = "<div class=\"page_preview\"><a href=\"#preview\">" . elgg_echo('sitepages:preview') . "</a></div>";

//construct the form
$form_body = <<<___EOT

	<p><label>$css_title
	$input_css</label></p>

	<p><label>$logged_in_content_title
	$input_logged_in_content</label></p>

	<p><label>$logged_out_content_title
	$input_logged_out_content</label></p>

	$hidden_guid
	$submit_input
	$preview

___EOT;

echo elgg_view('input/form', array('action' => "{$vars['url']}action/$action", 'body' => $form_body));