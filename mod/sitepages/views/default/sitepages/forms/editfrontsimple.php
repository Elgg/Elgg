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

$action = 'sitepages/addfrontsimple';

if($sitepages_object = sitepages_get_sitepage_object('frontsimple')){
	$welcometitle = $sitepages_object->welcometitle;
	$welcomemessage = $sitepages_object->welcomemessage;
	$sidebartitle = $sitepages_object->sidebartitle;
	$sidebarmessage = $sitepages_object->sidebarmessage;
}else{
	$welcometitle = "";
	$welcomemessage = "";
	$sidebartitle = "";
	$sidebarmessage = "";
}
	

// set the required form variables
$welcometitle_form = elgg_view('input/text', array('internalname' => 'welcometitle', 'value' => $welcometitle));
$welcomemessage_form = elgg_view('input/longtext', array('internalname' => 'welcomemessage', 'value' => $welcomemessage, 'class' => 'input_textarea monospace'));
$sidebartitle_form = elgg_view('input/text', array('internalname' => 'sidebartitle', 'value' => $sidebartitle));
$sidebarmessage_form = elgg_view('input/longtext', array('internalname' => 'sidebarmessage', 'value' => $sidebarmessage, 'class' => 'input_textarea monospace'));;
$submit_input = elgg_view('input/submit', array('internalname' => 'submit', 'value' => elgg_echo('save')));

$welcomemessage_title = elgg_echo("sitepages:welcomemessage");
$sidebarmessage_title = elgg_echo("sitepages:sidebarmessage");
$welcometitle_title = elgg_echo("sitepages:welcometitle");
$sidebartitle_title = elgg_echo("sitepages:sidebartitle");
$welcome_intro = elgg_echo("sitepages:welcomeintro");
$sidebar_intro = elgg_echo("sitepages:sidebarintro");

//construct the form
$form_body = <<<___EOT

	<h2>$welcome_intro</h2>
	<p><label>$welcometitle_title
	$welcometitle_form</label></p>
	<p><label>$welcomemessage_title
	$welcomemessage_form</label></p>

	<h2>$sidebar_intro</h2>
	<p><label>$sidebartitle_title
	$sidebartitle_form</label></p>
	<p><label>$sidebarmessage_title
	$sidebarmessage_form</label></p>

	$hidden_guid
	$submit_input
	
___EOT;

echo elgg_view('input/form', array('action' => "{$vars['url']}action/$action", 'body' => $form_body));