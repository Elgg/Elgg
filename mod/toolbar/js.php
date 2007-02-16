<?php

    /*
     *  Toolbar Javascript include
     */

     // We're going to need the main include file.
        require("../../includes.php");
     
     // And the config.
        global $CFG;
        
     // Prepare the page
        templates_page_setup();
        
     // Get the contents of the toolbar, sending the function some dummy data as input
        $toolbar = toolbar_mainbody('foo');
        
     // Which CSS file are we using?
        $css = optional_param('css','css');
        if ($css != "css") {
            $css = "css-box";
        }
        
     // Get the contents of the CSS
        $css = @file_get_contents($CFG->dirroot . "mod/toolbar/" . $css);
        $css = str_replace("{{url}}", $CFG->wwwroot, $css);
     
     // Strip out newlines
        $toolbar = str_replace("\n","",str_replace("\r","",$toolbar));
        $toolbar = addslashes($toolbar);
        $css = str_replace("\n","",str_replace("\r","",$css));
        $css = addslashes($css);
     
     // Add some JS to allow applications to know if we're logged in or not
        if (!isloggedin()) {
            $isloggedin = "false";
        } else {
            $isloggedin = "true";
        }
        
     // Send out a JS-like content type
        header("Content-type: text/javascript");
        
     // Now a little bit of Javascript
        echo <<< END
        
        var isLoggedIn = $isloggedin;
        
        document.write("$css");
        document.write("$toolbar");
        
END;

?>