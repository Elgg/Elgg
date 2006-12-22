<?php

global $CFG;
// Get the current profile ID
    
global $profile_id;

// Obtain the separate archive pages from the database

if ($archives = get_records_sql("SELECT DISTINCT EXTRACT(YEAR_MONTH FROM FROM_UNIXTIME(posted)) as archivestamp
                                    FROM ".$CFG->prefix."weblog_posts wp
                                    WHERE wp.weblog = ?",array($profile_id))) {
     
    // Get the name of the weblog user
    
    $weblog_name = optional_param('weblog_name', '', PARAM_ALPHANUM);
    
    // Run through them
    
    $run_result .= "<ul>";
    
    foreach($archives as $archive) {
        
        // Extract the year and the month
        
        $year = substr($archive->archivestamp, 0, 4);
        $month = substr($archive->archivestamp, 4, 2);
        
        // Print a link
        
        $run_result .= "<li>";
        $run_result .= "<a href=\"" . url . $weblog_name . "/weblog/archive/$year/$month/\">";
        $run_result .= date("F",gmmktime(0,0,0,$month,1,$year)) . " " . $year;
        $run_result .= "</a>";
        $run_result .= "</li>";
        
    }
    
    $run_result .= "</ul>";
    
    // If there are no posts to archive, say so!
    
} else {
    $noBlogs = __gettext("There are no weblog posts to archive as yet."); // gettext variable - NOT SURE ABOUT THIS POSITION
    $run_result .= "<p>$noBlogs</p>";
    
}

?>