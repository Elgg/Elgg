<?php

// Load Elgg framework
        @require_once("../../includes.php");
        
if (isloggedin()) {
        
	$id = optional_param('id',0,PARAM_INT); 
	$column = optional_param('column',0,PARAM_INT);
	$where = optional_param('where','');
	$id2 = optional_param('id2',0,PARAM_INT);
	if ($where == 'before') {
		$display_order = get_field('widgets','display_order','ident',$id2);
	} else {
		$display_order = 10000;
	}
	$widget = get_record('widgets','ident',$id);
	$page_owner = $widget->owner;
	if (run("permissions:check","profile")) {
		widget_move_before($widget,$display_order,$column);
		$result = '<?xml version="1.0" encoding="UTF-8" standalone="yes" ?>';
		$result .= '<answer>';
		$result .= '<wid>'.$id.'</wid>';
		//$result .= '<edit_form><![CDATA['.widget_edit($widget,1).']]></edit_form>';
		$result .= '</answer>';
		header('Content-Type: application/xml');
		print $result;
	}
}
?>