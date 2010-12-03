<?php
/**
 * Edit form for the custom front page
 *
 * @package SitePages
 */

if($sitepages_object = sitepages_get_sitepage_object('frontsimple')){
	$welcometitle = $sitepages_object->welcometitle;
	$welcomemessage = $sitepages_object->welcomemessage;
	$sidebartitle = $sitepages_object->sidebartitle;
	$sidebarmessage = $sitepages_object->sidebarmessage;
}else{
	$welcometitle = '';
	$welcomemessage = '';
	$sidebartitle = '';
	$sidebarmessage = '';
}
	

// set the required form variables
$welcometitle_form = elgg_view('input/text', array(
	'internalname' => 'welcometitle',
	'value' => $welcometitle
));
$welcomemessage_form = elgg_view('input/longtext', array(
	'internalname' => 'welcomemessage',
	'value' => $welcomemessage,
	'class' => 'input-textarea monospace'
));
$sidebartitle_form = elgg_view('input/text', array(
	'internalname' => 'sidebartitle',
	'value' => $sidebartitle
));
$sidebarmessage_form = elgg_view('input/longtext', array(
	'internalname' => 'sidebarmessage',
	'value' => $sidebarmessage,
	'class' => 'input-textarea monospace'
));;

$page_type = elgg_view('input/hidden', array(
	'internalname' => 'page_type',
	'value' => 'frontsimple',
));

$welcomemessage_title = elgg_echo('sitepages:welcomemessage');
$sidebarmessage_title = elgg_echo('sitepages:sidebarmessage');
$welcometitle_title = elgg_echo('sitepages:welcometitle');
$sidebartitle_title = elgg_echo('sitepages:sidebartitle');
$welcome_intro = elgg_echo('sitepages:welcomeintro');
$sidebar_intro = elgg_echo('sitepages:sidebarintro');

$ownfrontpage_message = elgg_echo('sitepages:ownfront');
$ownfrontpage = elgg_view('input/pulldown', array(
	'internalname' => 'params[ownfrontpage]',
	'value' => isset($vars['entity']->ownfrontpage) ? $vars['entity']->ownfrontpage : 'no',
	'options_values' => array(
		'yes' => elgg_echo('option:yes'),
		'no' => elgg_echo('option:no'),
	),
));

//construct the form
$form_body = <<<___EOT

	<p>
		$ownfrontpage_message
		$ownfrontpage
	</p>

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
	
	$page_type

___EOT;

echo $form_body;
