<?php

    /// TinyMCE editor

    global $CFG;
        global $function;
    
    // Add JS to embed file in weblog script
        $function['files:embed:js'][] = path . "units/tinymce/file_embed_js.php";

    // Only load the editor in the following contexts
        $function['weblogs:edit'][] = path . "units/tinymce/tinymce_js.php";
        $function['weblogs:posts:view:individual'][] = path . "units/tinymce/tinymce_js.php";

    // What is the user preference
        $function['userdetails:editor'][] = path . "units/tinymce/tinymce_userdetails.php";

    // Action handling
        $function['userdetails:init'][] = path . "units/tinymce/tinymce_userdetails_actions.php";
    // TODO figure this out
    // User details editable options
        $function['userdetails:edit:details'][] = path . "units/tinymce/tinymce_userdetails_edit.php";


    // Let the system know we've got TinyMCE loaded
    //$CFG->plugins->tinymce = true;

    // Added editor section to enable future editor choice in user config
    $CFG->plugins->editor["tinymce"] = true;
    
?>
