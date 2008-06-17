<?php
    /// TinyMCE editor <tinymce.moxiecode.com/> integration

    function tinymce_pagesetup() {
        global $CFG;
        global $function;
    
        // Add JS to embed file in weblog script
        $function['files:embed:js'][] = $CFG->dirroot . "mod/tinymce/file_embed_js.php";

        // Only load the editor in the following contexts
        $function['weblogs:edit'][] = $CFG->dirroot . "mod/tinymce/tinymce_js.php";
        $function['weblogs:posts:view:individual'][] = $CFG->dirroot . "mod/tinymce/tinymce_js.php";
        $function['tinymce:include'][] = $CFG->dirroot . "mod/tinymce/tinymce_js.php";

        // What is the user preference
        $function['userdetails:editor'][] = $CFG->dirroot . "mod/tinymce/tinymce_userdetails.php";
        
        // TODO figure this out
        // User details editable options
        $function['userdetails:edit:details'][] = $CFG->dirroot . "mod/tinymce/tinymce_userdetails_edit.php";

        // Added editor section to enable future editor choice in user config
        $CFG->plugins->editor["tinymce"] = true;
    }
    
    function tinymce_init() {
        global $CFG, $function;
        
        // Action handling (user preference)
        $function['userdetails:init'][] = $CFG->dirroot . "mod/tinymce/tinymce_userdetails_actions.php";
    }
?>
