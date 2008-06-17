<?php

// Load Elgg framework
@require_once("../../includes.php");

// Display comments for this object
// Currently this is a very simple layout with links back to the object display page
// at the top and bottom


$object_id = optional_param('object_id',0,PARAM_INT);
$object_type = optional_param('object_type','');

$title = _gettext("Bad page");
$body = "<p>".__gettext("Either this page does not exist or you do not have permission to view it.")."</p>";
if ($object_id && $object_type) {
	$access = get_access($object_id,$object_type,"read");
	// need to check access here - the user should have access to the comments
	// if and only if they have access to the object
	if ($access == 'PUBLIC' || run("users:access_level_check",$access)) {
		$title = __gettext('Comments on').' "'.get_title($object_id,$object_type).'"';
		$back = '<p><a href="'.get_url($object_id,$object_type).'">'.__gettext("Go back").'</a></p>';
		$body = $back;
		$body .= implode('',action('annotate',$object_id,$object_type,NULL,
		        array('comment_form_type'=>'separate')));
		$body .= $back;
	}
}

templates_page_setup();    
templates_page_output($title, $body);

?>