<?php

    // Gets RSS feed from URL supplied on $parameter
    
    
    // If we haven't already, initialise the RSS parser
        if (!defined('rss')) {    
            run("rss:init");
        }
        
    // Get the RSS feed
        $rss = @fetch_rss( $parameter );

    // Return RSS feed
        $run_result = $rss;
        
?>