<?php

// Load Elgg framework
        @require_once("../../includes.php");
        
$display_id = optional_param('display_id',0,PARAM_INT); 
$widget_type = optional_param('type','');
$widget_id = widget_create("profile",0,0,$widget_type,$_SESSION['userid'],"user" . $_SESSION['userid'],0);
$widget = get_record('widgets','ident',$widget_id);
$result = '<?xml version="1.0" encoding="UTF-8" standalone="yes" ?>';
$result .= '<answer>';
$result .= '<result>'.$display_id.','.$type</result>';
$result .= '<wid>'.$widget_id.'</wid>';
$result .= '<edit_form><![CDATA['.widget_edit($widget).']]></edit_form>'
$result .= '</answer>';
header('Content-Type: application/xml');
print $result;
?>