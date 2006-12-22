<?php

global $CFG;
// Get the current profile ID
    
global $profile_id;

// Obtain the separate archive pages from the database
if ($CFG->dbtype == 'mysql') {
    $field = 'EXTRACT(YEAR_MONTH FROM FROM_UNIXTIME(posted))';
} elseif ($CFG->dbtype == 'postgres7') {
    $field = 'to_char(TIMESTAMP WITH TIME ZONE \'epoch\' + posted * interval \'1 second\',\'YYYYMM\')';
}
if ($archives = get_records_sql('SELECT DISTINCT '.$field.' as archivestamp, posted
                                    FROM '.$CFG->prefix.'weblog_posts wp
                                    WHERE wp.weblog = ? ORDER BY posted DESC',array($profile_id))) {

// If there are any archives ...
$archive = __gettext("Weblog Archive"); // gettext variable
$run_result .= "<h1 class=\"weblogdateheader\">$archive</h1>";
    
    // Get the name of the weblog user
    
    $weblog_name = htmlspecialchars(optional_param('weblog_name'), ENT_COMPAT, 'utf-8');
    
    // Run through them
    
    $lastyear = 0;
    
    foreach($archives as $archive) {
        
        // Extract the year and the month
        
        $year = substr($archive->archivestamp, 0, 4);
        $month = substr($archive->archivestamp, 4, 2);
        
        if ($year != $lastyear) {
            if ($lastyear .= 0) {
                $run_result .= "</ul>";
            }
            $lastyear = $year;
            $run_result .= "<h2 class=\"weblogdateheader\">$year</h2>";
            $run_result .= "<ul>";
        }
        
        // Print a link
        
        $run_result .= "<li>";
        $run_result .= "<a href=\"" . url . $weblog_name . "/weblog/archive/$year/$month/\">";
        $run_result .= strftime("%B %Y", gmmktime(0,0,0,$month,1,$year));
        $run_result .= "</a>";
        $run_result .= "</li>";
        
    }
    
    $run_result .= "</ul>";
    
    // If there are no posts to archive, say so!
 
} else {
    
    $run_result .= "<p>" . __gettext("There are no weblog posts to archive as yet.") . "</p>";
    
}
    
?>