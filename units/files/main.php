<?php

    /*
    *    File repository plug-in
    */

        global $CFG;
    
    // Functions to perform upon initialisation
        $function['files:init'][] = $CFG->dirroot . "units/files/files_init.php";
        $function['files:init'][] = $CFG->dirroot . "units/files/metadata_defaults.php";
        $function['init'][] = $CFG->dirroot . "units/files/inline_mimetypes.php";
    
    // Actions to perform
        $function['files:init'][] = $CFG->dirroot . "units/files/files_actions.php";

    // Init for search
        $function['search:init'][] = $CFG->dirroot . "units/files/files_init.php";
        $function['search:all:tagtypes'][] = $CFG->dirroot . "units/files/function_search_all_tagtypes.php";
        
    // Function to search through weblog posts
        $function['search:display_results'][] = $CFG->dirroot . "units/files/function_search.php";
        $function['search:display_results:rss'][] = $CFG->dirroot . "units/files/function_search_rss.php";
        
    // Determines whether or not a file should be displayed in the browser
        $function['files:mimetype:inline'][] = $CFG->dirroot . "units/files/files_mimetype_inline.php";
        
    // View files
        $function['files:view'][] = $CFG->dirroot . "units/files/files_view.php";

    // View the contents of a specific folder
        $function['files:folder:view'][] = $CFG->dirroot . "units/files/folder_view.php";
        
    // Edit the contents of a specific folder
        $function['files:folder:edit'][] = $CFG->dirroot . "units/files/edit_folder.php";
        
    // Edit the metadata for a specific file
        $function['files:edit'][] = $CFG->dirroot . "units/files/edit_file.php";
        $function['folder:select'][] = $CFG->dirroot . "units/files/select_folder.php";
    
    // Edit metadata
        $function['metadata:edit'][] = $CFG->dirroot . "units/files/metadata_edit.php";
        
    // Turn file ID into a link
        $function['files:links:make'][] = $CFG->dirroot . "units/files/files_links_make.php";
        
    // Load default template
        $function['init'][] = $CFG->dirroot . "units/files/default_templates.php";

    // Allow users to embed files in weblog posts
        $function['weblogs:posts:add:fields'][] = $CFG->dirroot . "units/files/weblogs_posts_add_fields.php";
        $function['weblogs:posts:edit:fields'][] = $CFG->dirroot . "units/files/weblogs_posts_add_fields.php";
        $function['weblogs:text:process'][] = $CFG->dirroot . "units/files/weblogs_text_process.php";
                    
    // Log on bar down the right hand side
        $function['display:sidebar'][] = $CFG->dirroot . "units/files/files_user_info_menu.php";
        
    // Template preview
        $function['templates:preview'][] = $CFG->dirroot . "units/files/templates_preview.php";

    // Establish permissions
        $function['permissions:check'][] = $CFG->dirroot . "units/files/permissions_check.php";

    // Actions to perform when an access group is deleted
        $function['groups:delete'][] = $CFG->dirroot . "units/files/groups_delete.php";

    // Publish static RSS file of files
        $function['files:rss:getitems'][] = $CFG->dirroot . "units/files/function_rss_getitems.php";
        $function['files:rss:publish'][] = $CFG->dirroot . "units/files/function_rss_publish.php";
        
?>
