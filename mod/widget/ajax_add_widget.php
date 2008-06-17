<?php

// Load Elgg framework
        @require_once("../../includes.php");
        
global $CFG;

$display_id = optional_param('display_id',0,PARAM_INT); 
$widget_type = optional_param('type','');
$column = optional_param('column',0,PARAM_INT);
$before = optional_param('before',0,PARAM_INT);
$owner = optional_param('owner', 0, PARAM_INT);

if ($before) {
	$display_order = get_field('widgets','display_order','ident',$before)-1;
} else {
	$display_order = 0;
}

if (empty($owner)) {
    $owner = $_SESSION['userid'];
}

$widget_id = widget_create("profile",0,$column,$widget_type,$owner,$CFG->default_access,$display_order);
$widget = get_record('widgets','ident',$widget_id);
$result = '<?xml version="1.0" encoding="UTF-8" standalone="yes" ?>';
$result .= '<answer>';
$result .= '<result>'.$display_id.'</result>';
$result .= '<uid>'.$_SESSION['userid'].'</uid>';
$result .= '<wid>'.$widget_id.'</wid>';
$result .= '<edit_form><![CDATA['.widget_edit($widget,1).']]></edit_form>';
$result .= '</answer>';
header('Content-Type: application/xml');
print $result;
?>