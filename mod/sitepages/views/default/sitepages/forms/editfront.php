<?php
/**
 * Elgg edit frontpage
 */
	 
//action
$action = "sitepages/addfront";
	 
//grab the required entity
$page_contents = elgg_get_entities(array('type' => 'object', 'subtype' => 'frontpage', 'limit' => 1));
	 
if($page_contents){
	 foreach($page_contents as $pc){
		 $css = $pc->title;
		 $frontContents = $pc->description;
		 $guid = $pc->guid;
	 }
}else {		
	$tags = "";
	$description = "";
}
		
// set the required form variables
$input_css = elgg_view('input/plaintext', array('internalname' => 'css', 'value' => $css));
$input_pageshell = elgg_view('input/plaintext', array('internalname' => 'frontContents', 'value' => $frontContents));
$submit_input = elgg_view('input/submit', array('internalname' => 'submit', 'value' => elgg_echo('save')));
$hidden_guid = elgg_view('input/hidden', array('internalname' => 'front_guid', 'value' => $guid));
$pageshell = elgg_echo("sitepages:frontContents");
$css = elgg_echo("sitepages:css");
		
//preview link
$preview = "<div class=\"page_preview\"><a href=\"#preview\">" . elgg_echo('sitepages:preview') . "</a></div>";
		
//construct the form
$form_body = <<<EOT

	<h3 class='settings'>$css</h3>
	<p class='longtext_editarea'>$input_css</p><br />
	<h3 class='settings'>$pageshell</h3>
	<p class='longtext_editarea'>$input_pageshell</p>
		
	$hidden_guid
	<br />
	$submit_input
	$preview

EOT;
?>
<?php
	//display the form
	echo elgg_view('input/form', array('action' => "{$vars['url']}action/$action", 'body' => $form_body));
?>