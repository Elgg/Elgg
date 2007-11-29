<?php

// Load Elgg framework
        @require_once("../../includes.php");
        
if (isloggedin()) {        
	$id = optional_param('id',0,PARAM_INT);	
	$widget = get_record('widgets','ident',$id);	
	$page_owner = $widget->owner;
	if (run("permissions:check","profile")) {
		$result = '<?xml version="1.0" encoding="UTF-8" standalone="yes" ?>';
		$result .= '<answer>';
		$result .= '<wid>'.$id.'</wid>';
		$result .= '<edit_form><![CDATA['.widget_edit($widget,1).']]></edit_form>';
		$result .= '</answer>';
		header('Content-Type: application/xml');
		print $result;
	}
}
?>