<?php
global $USER,$CFG;
// Content flag list
    
if (logged_on && user_flag_get("admin", $USER->ident)) {
    
    $run_result .= "<p>" . __gettext("The following pages have been flagged as having obscene or inappropriate content. They are ordered by number of complaints.") . "</p>";
    $run_result .= "<p>" . __gettext("To view the pages in question, click the following links. To remove the flags, for example if the flag is a false positive or if you've deleted the offending content, check the appropriate box and click the 'delete' button below.") . "</p>";
    
    $run_result .= "<form action=\"\" method=\"post\">";
        
    if ($flags = get_records_sql('SELECT DISTINCT url,count(ident) AS totalflags
                                  FROM '.$CFG->prefix.'content_flags 
                                  GROUP BY url ORDER BY totalflags desc')) {
        $run_result .= templates_draw(array(
                                            'context' => 'adminTable',
                                            'name' => "&nbsp;",
                                            'column1' => "<b>" . __gettext("Page URL") . "</b>",
                                            'column2' => "<b>" . __gettext("Number of objections") . "</b>"
                                            )
                                      );
        
        foreach($flags as $flag) {
            
            $run_result .= templates_draw(array(
                                                'context' => 'adminTable',
                                                'name' => "<input type=\"checkbox\" name=\"remove[]\" value=\"" . $flag->url . "\" />",
                                                'column1' => "<a href=\"" . $flag->url . "\" target=\"_blank\">" . $flag->url . "</a>",
                                                'column2' => $flag->totalflags
                                                )
                                          );
            
        }
        
        $run_result .= templates_draw(array(
                                            'context' => 'adminTable',
                                            'name' => "&nbsp;",
                                            'column1' => "<input type=\"submit\" value=\"".__gettext("Remove flag(s)")."\" />",
                                            'column2' => "<input type=\"hidden\" name=\"action\" value=\"content:flags:delete\" />"
                                            )
                                      );
        
    } else {
        $run_result .= "<p>" . __gettext("No content flags were found at present.") . "</p>";
    }
    
    $run_result .= "</form>";
    
}
    
?>