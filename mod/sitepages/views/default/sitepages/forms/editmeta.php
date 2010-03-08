<?php
/**
 * Elgg edit frontpage
 */
	 
//action
$action = "sitepages/addmeta";
	 
//grab the required entity
$page_contents = elgg_get_entities(array('type' => 'object', 'subtype' => 'sitemeta', 'limit' => 1));
	 
if($page_contents){
	 foreach($page_contents as $pc){
		 $metatags = $pc->title;
		 $description = $pc->description;
		 $guid = $pc->guid;
	 }
}else {		
	$metatags = "";
	$description = "";
}
		
// set the required form variables
$input_keywords = elgg_view('input/plaintext', array('internalname' => 'metatags', 'value' => $metatags));
$input_description = elgg_view('input/plaintext', array('internalname' => 'description', 'value' => $description));
$submit_input = elgg_view('input/submit', array('internalname' => 'submit', 'value' => elgg_echo('save')));
$hidden_guid = elgg_view('input/hidden', array('internalname' => 'seo_guid', 'value' => $guid));
$description = elgg_echo("sitepages:metadescription");
$metatags = elgg_echo("sitepages:metatags");
			
//construct the form
$form_body = <<<EOT

	<h3 class='settings'>$description</h3>
	<p class='longtext_editarea'>$input_description</p><br />
	<h3 class='settings'>$metatags</h3>
	<p class='longtext_editarea'>$input_keywords</p>
		
	$hidden_guid
	<br />
	$submit_input

EOT;
?>
<?php
	//display the form
	echo elgg_view('input/form', array('action' => "{$vars['url']}action/$action", 'body' => $form_body));
?>