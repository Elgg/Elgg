<?php

    /*
    *    Weblog plug-in
    */

    // Functions to perform upon initialisation
        $function['weblogs:init'][] = $CFG->dirroot . "units/weblogs/weblogs_init.php";
        $function['weblogs:init'][] = $CFG->dirroot . "units/weblogs/weblogs_actions.php";
        
    // Load default template
        $function['init'][] = $CFG->dirroot . "units/weblogs/default_template.php";
    
    // Init for search
        $function['search:init'][] = $CFG->dirroot . "units/weblogs/weblogs_init.php";
        $function['search:all:tagtypes'][] = $CFG->dirroot . "units/weblogs/function_search_all_tagtypes.php";
        
    // Function to search through weblog posts
        $function['search:display_results'][] = $CFG->dirroot . "units/weblogs/function_search.php";
        $function['search:display_results:rss'][] = $CFG->dirroot . "units/weblogs/function_search_rss.php";
        
    // Edit / create weblog posts
        $function['weblogs:edit'][] = $CFG->dirroot . "units/weblogs/weblogs_edit.php";
        $function['weblogs:posts:add'][] = $CFG->dirroot . "units/weblogs/weblogs_posts_add.php";
        $function['weblogs:posts:edit'][] = $CFG->dirroot . "units/weblogs/weblogs_posts_edit.php";
        
    // View weblog posts
        $function['weblogs:view'][] = $CFG->dirroot . "units/weblogs/weblogs_post_field_wrapper.php";
        $function['weblogs:view'][] = $CFG->dirroot . "units/weblogs/weblogs_view.php";
        $function['weblogs:posts:view'][] = $CFG->dirroot . "units/weblogs/weblogs_posts_view.php";
        $function['weblogs:posts:view:individual'][] = $CFG->dirroot . "units/weblogs/weblogs_posts_view.php";
        $function['weblogs:friends:view'][] = $CFG->dirroot . "units/weblogs/weblogs_friends_view.php";
        $function['weblogs:everyone:view'][] = $CFG->dirroot . "units/weblogs/weblogs_all_users_view.php";
        $function['weblogs:text:process'][] = $CFG->dirroot . "units/weblogs/weblogs_text_process.php";
        $function['weblogs:archives:view'][] = $CFG->dirroot . "units/weblogs/archives_view.php";
        $function['weblogs:archives:month:view'][] = $CFG->dirroot . "units/weblogs/weblogs_view_month.php";
        $function['weblogs:interesting:view'][] = $CFG->dirroot . "units/weblogs/weblogs_interesting_view.php";
        
    // Mark posts as interesting (or not)
        $function['weblogs:interesting:form'][] = $CFG->dirroot . "units/weblogs/display_interesting_post_form.php";
        
    // Edit / create weblog comments
        $function['weblogs:comments:add'][] = $CFG->dirroot . "units/weblogs/weblogs_comments_add.php";
        
    // Log on bar down the right hand side
        // $function['profile:log_on_pane'][] = $CFG->dirroot . "units/weblogs/weblogs_user_info_menu.php";
        $function['display:sidebar'][] = $CFG->dirroot . "units/weblogs/weblogs_user_info_menu.php";
        
    // Weblog preview
        $function['templates:preview'][] = $CFG->dirroot . "units/weblogs/templates_preview.php";
        
    // Establish permissions
        $function['permissions:check'][] = $CFG->dirroot . "units/weblogs/permissions_check.php";
        
    // Actions to perform when an access group is deleted
        $function['groups:delete'][] = $CFG->dirroot . "units/weblogs/groups_delete.php";
        
    // Publish static RSS file of posts
        $function['weblogs:rss:getitems'][] = $CFG->dirroot . "units/weblogs/function_rss_getitems.php";
        $function['weblogs:rss:publish'][] = $CFG->dirroot . "units/weblogs/function_rss_publish.php";
        
    // Removing function from weblogs_init.php
        $function['weblogs:html_activate_urls'][] = $CFG->dirroot . "units/weblogs/function_html_activate_urls.php";
?>