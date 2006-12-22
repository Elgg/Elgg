<?php

    global $page_owner;
    
    if (logged_on && $page_owner != $_SESSION['userid']) {

        $page_url = $_SERVER['REQUEST_URI'];

        // TODO NZVLE - this didn't work in subdirectories
        // we should make it post to something like flag_content.php
        // but for now just make it post to itself 

        //strip off anything past the first slash (after http://)
        $hostpart = substr(url,0,strpos(url,'/',10)+1); // offset 10 to be safe.
        $url = $hostpart . ltrim($page_url,'/');

        $run_result .= "<p>&nbsp;</p>";
        $run_result .= "<form action=\"".$url."\" method=\"post\" >";
    
        
        $run_result .= templates_draw(array(
                            'context' => 'flagContent',
                            'name' => "<h5>" . __gettext("Flag content") . "</h5>",
                            'column1' => "<p>" . __gettext("To mark this content as obscene or inappropriate, click the 'Flag' button and an administrator will view it in due course.") . "</p>",
                            'column2' => "<input type=\"submit\" value=\"" . __gettext("Flag") . "\" /><input type=\"hidden\" name=\"action\" value=\"content:flag\" /><input type=\"hidden\" name=\"address\" value=\"$page_url\" />"
                        )
                        );
        $run_result .= "</form>";
        
    }

?>