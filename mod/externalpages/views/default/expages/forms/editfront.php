<?php

	/**
	 * Elgg edit frontpage
	 * 
	 * @package ElggExpages
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.com/
	 * 
	 */
	 
	 //action
	 $action = "expages/addfront";
	 
	 //grab the required entity
	 $page_contents = elgg_get_entities(array('type' => 'object', 'subtype' => 'front', 'limit' => 1));
	 
	if($page_contents){
		 foreach($page_contents as $pc){
			 $description_right = $pc->description;
			 $description_left = $pc->title;
			 $guid = $pc->guid;
		 }
	}else {		
		$description = "";
	}
		
	// set the required form variables
		$input_area_left = elgg_view('input/longtext', array('internalname' => 'front_left', 'value' => $description_left));
		$input_area_right = elgg_view('input/longtext', array('internalname' => 'front_right', 'value' => $description_right));
		$submit_input = elgg_view('input/submit', array('internalname' => 'submit', 'value' => elgg_echo('save')));
		$hidden_guid = elgg_view('input/hidden', array('internalname' => 'front_guid', 'value' => $guid));
		$lefthand = elgg_echo("expages:lefthand");
		$righthand = elgg_echo("expages:righthand");
		
	//preview link
	//	echo "<div class=\"page_preview\"><a href=\"#preview\">" . elgg_echo('expages:preview') . "</a></div>";
		
	//construct the form
		$form_body = <<<EOT

		<h3 class='settings'>$lefthand</h3>
		<p class='longtext_editarea'>$input_area_left</p><br />
		<h3 class='settings'>$righthand</h3>
		<p class='longtext_editarea'>$input_area_right</p>
		
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
	if($description_left){
		echo "The left column header space<br />";
		echo $description_left;
	}
	if($description_right){
		echo "The right column header space<br />";
		echo $description_right;
	}else
		echo elgg_echo('expages:nopreview');
 */
?>
</div>
-->