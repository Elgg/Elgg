<?php

    global $page_owner;
        
    if (logged_on && $page_owner != $_SESSION['userid'] && isset($parameter)) {

        $page_url = $_SERVER['REQUEST_URI'];

        // TODO NZVLE - this didn't work in subdirectories
        // we should make it post to something like flag_content.php
        // but for now just make it post to itself 

        //strip off anything past the first slash (after http://)
        $hostpart = substr(url,0,strpos(url,'/',10)+1); // offset 10 to be safe.
        $url = $hostpart . ltrim($page_url,'/');
    
        $run_result .= "<p>&nbsp;</p>";
        $run_result .= "<form action=\"" . $url . "\" method=\"post\" >";

        if (record_exists('weblog_watchlist','weblog_post',$parameter,'owner',$_SESSION['userid'])) {
            $name = __gettext("Stop keeping track of this post");
            $column1 = "<p>" . __gettext("You have marked this post as interesting; all comments will appear on your 'recent activity' page. If you would like to remove this flag, click here.") . "</p>";
            $column2 = "<input type=\"submit\" value=\"" . __gettext("Remove interesting flag") . "\" /><input type=\"hidden\" name=\"action\" value=\"weblog:interesting:off\" /><input type=\"hidden\" name=\"weblog_post\" value=\"$parameter\" />";
        } else {
            $name = __gettext("Keep track of this post");
            $column1 = "<p>" . __gettext("Click the 'Mark interesting' button to monitor new comments on your 'recent activity' page.") . "</p>";
            $column2 = "<input type=\"submit\" value=\"" . __gettext("Mark interesting") . "\" /><input type=\"hidden\" name=\"action\" value=\"weblog:interesting:on\" /><input type=\"hidden\" name=\"weblog_post\" value=\"$parameter\" />";
        }
        
        $run_result .= templates_draw(array(
                                            'context' => 'flagContent',
                                            'name' => "<h5>" . $name . "</h5>",
                                            'column1' => $column1,
                                            'column2' => $column2
                                            )
                                      );
        $run_result .= "</form>";
        
    }

?>