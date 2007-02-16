<?php

    // Magpie unit for Elgg
    // ben@elgg.net Oct 17, 2005

    // Library functions
        require_once($CFG->dirroot . "units/magpie/library.php");    
    
    // Load default template
        $function['init'][] = $CFG->dirroot . "units/magpie/default_template.php";

    // Initialise RSS parser
        $function['rss:init'][] = $CFG->dirroot . "units/magpie/function_init.php";
        $function['rss:init'][] = $CFG->dirroot . "units/magpie/function_actions.php";
    // Get current contents of a feed (raw)
        $function['rss:get'][] = $CFG->dirroot . "units/magpie/function_get.php";
    // Display a user's subscriptions
        $function['rss:subscriptions'][] = $CFG->dirroot . "units/magpie/function_subscriptions.php";
    // Allow a user to publish feeds to their blog
        $function['rss:subscriptions:publish:blog'][] = $CFG->dirroot . "units/magpie/function_subscriptions_publish_to_blog.php";
    // Load variable containing all subscriptions for a user
        $function['rss:subscriptions:get'][] = $CFG->dirroot . "units/magpie/function_get_subscriptions.php";
    // Display the most popular subscriptions
        $function['rss:subscriptions:popular'][] = $CFG->dirroot . "units/magpie/function_subscriptions_popular.php";
    // Update a feed by ID
        $function['rss:update'][] = $CFG->dirroot . "units/magpie/function_update.php";
    // Update all feeds by user
        $function['rss:update:all'][] = $CFG->dirroot . "units/magpie/function_update_all.php";
    // Update all feeds in system (for use with cron job)
        $function['rss:update:all:cron'][] = $CFG->dirroot . "units/magpie/function_update_all_cron.php";

    // Permissions check
        $function['permissions:check'][] = $CFG->dirroot . "units/magpie/permissions_check.php";
        
    // View a user's posts
        $function['rss:view'][] = $CFG->dirroot . "units/magpie/function_view.php";
        $function['rss:view:feed'][] = $CFG->dirroot . "units/magpie/function_view_individual.php";
        $function['rss:view:post'][] = $CFG->dirroot . "units/magpie/function_view_post.php";

    // Is the current user subscribed to a feed?
        $function['rss:subscribed'][] = $CFG->dirroot . "units/magpie/function_is_subscribed.php";

    // Prune feed posts older than a configured age
        $function['rss:prune'][] = $CFG->dirroot . "units/magpie/function_prune.php";

?>
