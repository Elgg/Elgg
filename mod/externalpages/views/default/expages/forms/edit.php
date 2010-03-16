<?php

	/**
	 * Elgg External pages edit
	 * 
	 * @package ElggExpages
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.com/
	 * 
	 */
	 
	 //get the page type
	 $type = $vars['type'];
	 
	 //action
	 $action = "expages/add";
	 
	 //grab the required entity
	 $page_contents = elgg_get_entities(array('type' => 'object', 'subtype' => $type, 'limit' => 1));
	 
	if($page_contents){
		 foreach($page_contents as $pc){
			 $description = $pc->description;
			 $guid = $pc->guid;
		 }
	}else {		
		$description = "";
	}
		
	// set the required form variables
		$input_area = elgg_view('input/longtext', array('internalname' => 'expagescontent', 'value' => $description));
        $submit_input = elgg_view('input/submit', array('internalname' => 'submit', 'value' => elgg_echo('save')));
		$hidden_value = elgg_view('input/hidden', array('internalname' => 'content_type', 'value' => $type));
		$hidden_guid = elgg_view('input/hidden', array('internalname' => 'expage_guid', 'value' => $guid));
		
		//type
		$type = $vars['type'];
		//set the url
		$url = $vars['url'] . "pg/expages/index.php?type=";
		
		if($type == 'about') { 
			$external_page_title = elgg_echo('expages:about');
		}
		else if($type == 'terms') {
			$external_page_title = elgg_echo('expages:terms');
		}
		else if($type == 'privacy') {
			$external_page_title = elgg_echo('expages:privacy');     
		}
	//preview link
	//	echo "<div class=\"page_preview\"><a href=\"#preview\">" . elgg_echo('expages:preview') . "</a></div>";
		
	//construct the form
		$form_body = <<<EOT

		<h3 class='settings'>$external_page_title</h3>
		<p class='longtext_editarea'>$input_area</p>
			$hidden_value
			$hidden_guid
			<br />
			$submit_input

EOT;
?>
<?php
	//display the form
	echo elgg_view('input/form', array('action' => "{$vars['url']}action/$action", 'body' => $form_body));
?>

<!-- preview page contents -->
<!--
<div class="expage_preview">
<a name="preview"></a>
<h2>Preview</h2>
<?php 
/*
	if($description)
		echo $description;
	else
		echo elgg_echo('expages:nopreview');
*/
?>
</div>
-->