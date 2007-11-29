<?php

// Load Elgg framework
@require_once("../../includes.php");

$id = optional_param('id',0,PARAM_INT);

if (isloggedin()) {
    if ($widget = get_record('widgets','ident',$id)) {
        
        // Page owner = where the widget resides
        $page_owner = $widget->owner;
        
        // Do we have permission to touch this?
        // If so, wipe it!
        if (run("permissions:check","profile")) {
            
            widget_destroy($widget->ident);
            widget_reorder($page_owner,$widget->location,$widget->location_id,$widget->column);
            
        }
    }
}

$result = '<?xml version="1.0" encoding="UTF-8" standalone="yes" ?>';
$result .= '<answer>';
$result .= '<wid>'.$id.'</wid>';
$result .= '</answer>';
header('Content-Type: application/xml');
print $result;
?>