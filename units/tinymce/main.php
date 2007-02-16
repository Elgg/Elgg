<?php

    /// TinyMCE editor

    global $CFG;
        global $function;
    
    // Add JS to embed file in weblog script
        $function['files:embed:js'][] = $CFG->dirroot . "units/tinymce/file_embed_js.php";

    // Only load the editor in the following contexts
        $function['weblogs:edit'][] = $CFG->dirroot . "units/tinymce/tinymce_js.php";
        $function['weblogs:posts:view:individual'][] = $CFG->dirroot . "units/tinymce/tinymce_js.php";
        $function['tinymce:include'][] = $CFG->dirroot . "units/tinymce/tinymce_js.php";

    // What is the user preference
        $function['userdetails:editor'][] = $CFG->dirroot . "units/tinymce/tinymce_userdetails.php";

    // Action handling
        $function['userdetails:init'][] = $CFG->dirroot . "units/tinymce/tinymce_userdetails_actions.php";
    // TODO figure this out
    // User details editable options
        $function['userdetails:edit:details'][] = $CFG->dirroot . "units/tinymce/tinymce_userdetails_edit.php";


    // Let the system know we've got TinyMCE loaded
    //$CFG->plugins->tinymce = true;

    // Added editor section to enable future editor choice in user config
    $CFG->plugins->editor["tinymce"] = true;
    
?>
