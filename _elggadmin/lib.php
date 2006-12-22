<?php

    /*
    
        Elgg admin panel
        Main library
    
    */

    // We want to load the config file whenever we can
        require_once("config.php");

    // Initialisation    
        function elggadmin_init() {
            global $CFG, $ADMINCFG, $messages;
            
            if (file_exists($ADMINCFG->admin->elggdir . $ADMINCFG->admin->configfile)) {
                
                // Activities to perform if Elgg's config file exists
                
                // Load Elgg configuration
                global $CFG;
                require_once($ADMINCFG->admin->elggdir . $ADMINCFG->admin->configfile);
                
                // Begin Elgg admin session
                session_name("elggadmin");
                session_start();
                
                if (isset($_SESSION['messages'])) {
                    $messages = $_SESSION['messages'];
                    $_SESSION['messages'] = "";
                }
                
                // Set the templates root if it doesn't exist
                if (!isset($CFG->templatesroot)) {
                    $CFG->templatesroot = $CFG->dirroot . "_templates/";
                }
                
                // Check logins etc
                elggadmin_actions();
                
                // If we're not logged in, force the log in panel
                if (!elggadmin_isloggedin()) {

                    elggadmin_header();
                    elggadmin_begin_content();
                    elggadmin_loginbox();
                    elggadmin_end_content();
                    elggadmin_footer();
                    exit;

                }
                
            } else {
                
                // Activities to perform if Elgg's config file doesn't exist
                elggadmin_createconfig();
                
            }
        }
    
        function elggadmin_actions() {
            
            global $CFG, $ADMINCFG, $messages;
            
            if (isset($_REQUEST['action'])) {
                $action = $_REQUEST['action'];
            }
            
            switch($action) {
                
                case "admin:login":
                                        elggadmin_checklogins();
                                        break;
                case "config:save":
                                        elggadmin_config_save();
                                        break;
                case "theme:save":
                                        elggadmin_save_template();
                                        break;
                case "frontpage:save":
                                        elggadmin_save_frontpage();
                                        break;
                case "theme:backup:save":
                                        elggadmin_make_backup();
                                        $_SESSION['messages'] = $messages;
                                        header("Location: theme.php");
                                        exit;
                                        break;
                case "theme:backup:restore":
                                        elggadmin_restore_backup();
                                        $_SESSION['messages'] = $messages;
                                        header("Location: theme.php");
                                        exit;
                                        break;
                
            }
            
        }
        
        function elggadmin_checklogins() {
            
            global $CFG, $ADMINCFG, $messages;
            
            $username = "";
            $password = "";
            
            if (isset($_REQUEST['adminuser'])) {
                $username = $_REQUEST['adminuser'];
            }
            if (isset($_REQUEST['adminpassword'])) {
                $password = $_REQUEST['adminpassword'];
            }

            if (!empty($username) && !empty($password)
                && !empty($CFG->adminuser) && !empty($CFG->adminpassword) ) {
                
                    if ($username == $CFG->adminuser
                        && $password = $CFG->adminpassword) {
                            
                            $_SESSION['adminid'] = 1;
                            $_SESSION['admincode'] = md5($CFG->adminuser . $CFG->adminpassword);
                            $messages[] = ("You logged in.");
                            $_SESSION['messages'] = $messages;
                            header("Location: index.php");
                            exit;
                            
                        } else {
                            $messages[] = ("The username and password you specified did not match the admin details for this system.");
                        }
                    
            }
            
        }
        
        
    // Initialisation for config editing
        function elggadmin_config_init() {
            
            global $CFG, $PARSEDCFG, $ADMINCFG, $messages;
            
            if (!isset($PARSEDCFG)) {
            
                $PARSEDCFG = new stdClass();
                
                foreach(get_object_vars($CFG) as $key => $value) {
                    $PARSEDCFG->$key = addslashes($value);
                }
            
            }

            include("configdef.php");
            $ADMINCFG->config = $DEFCFG->config;
                        
            
        }
        
    // Display the form to set configuration options
        function elggadmin_config_main() {
            
            global $CFG, $PARSEDCFG, $ADMINCFG, $messages;
            
            echo "<form action=\"\" method=\"post\">";
            foreach(get_object_vars($CFG) as $name => $value) {
                
                if (!in_array($name,$ADMINCFG->admin->noedit)) {
                    echo "<p>";
                    if (isset($ADMINCFG->config[$name]->name)) {
                        echo "<b>" . $ADMINCFG->config[$name]->name . "</b>";
                    }
                    if (isset($ADMINCFG->config[$name]->description)) {
                        echo "<br /><i>" . $ADMINCFG->config[$name]->description . "</i>";
                    }
                    echo "</p>\n";
                    echo "<p>";
                    
                    if (isset($ADMINCFG->config[$name]->type)) {
                        switch($ADMINCFG->config[$name]->type) {
                            
                            case "requiredstring":
                            case "integer":     echo "<input type=\"text\" name=\"$name\" value=\"" . htmlspecialchars($value) . "\" />";
                                                break;
                            case "access":
                                                $selected = array();
                                                $selected[$CFG->$name] = "selected=\"selected\"";
                                                echo "<select name=\"$name\">";
                                                echo "<option value=\"PUBLIC\" {$selected['PUBLIC']}>" . ("Public") . "</option>";
                                                echo "<option value=\"LOGGED_IN\" {$selected['LOGGED_IN']} >" . ("Logged in users only") . "</option>";
                                                echo "<option value=\"PRIVATE\" {$selected['PRIVATE']} >" . ("Private") . "</option>";
                                                echo "</select>";
                                                break;
                            case "boolean":
                                                $value = (int) $value;
                                                $selected = array();
                                                if ($value) {
                                                    $selected['yes'] = "selected=\"selected\"";
                                                } else {
                                                    $selected['no'] = "selected=\"selected\"";
                                                }
                                                echo "<select name=\"$name\">";
                                                echo "<option value=\"0\" {$selected['no']}>" . ("No") . "</option>";
                                                echo "<option value=\"1\" {$selected['yes']} >" . ("Yes") . "</option>";
                                                echo "</select>";
                                                break;
                            
                        }
                    } else {
                        echo "<input type=\"text\" name=\"$name\" value=\"" . htmlspecialchars($value) . "\" />";
                    }
                    
                    echo "</p>\n";
                }
                
            }
            
            echo "<p>&nbsp;</p><p><i>" . ("Click below to save your settings.") . "</i></p>";
            echo "<p><input type=\"hidden\" value=\"config:save\" name=\"action\" /><input type=\"submit\" value=\"" . ("Save") . "\" /></p>";
            echo "</form>";
        }

    // Save configuration settings to Elgg's config.php        
        function elggadmin_config_save() {
            
            global $CFG, $ADMINCFG, $PARSEDCFG, $DEFCFG, $messages;
            
            $oktosave = 1;
            
            foreach(get_object_vars($CFG) as $name => $value) {
                
                if (!in_array($name,$ADMINCFG->admin->noedit)) {
                    if (isset($_REQUEST[$name])) {
                        
                        $CFG->$name = substr($_REQUEST[$name],0,128);
                        if(ini_get("magic_quotes_gpc")) {
                            $CFG->$name = stripslashes($CFG->$name);
                        }
                        
                    }
                }
                        if (isset($DEFCFG->config[$name]->type)) {
                            
                            switch ($DEFCFG->config[$name]->type) {
                                
                                case "int":
                                            if (empty($CFG->$name)) {
                                                $CFG->$name = "0";
                                            }
                                            $CFG->$name = (int) $CFG->$name;
                                            break;
                                case "boolean":
                                            if (empty($CFG->$name)) {
                                                $CFG->$name = "0";
                                            }
                                            $CFG->$name = (int) $CFG->$name;
                                            if ($CFG->$name > 1) {
                                                $CFG->$name = "1";
                                            }
                                            break;
                                case "requiredstring":
                                            if (empty($CFG->$name)) {
                                                $oktosave = 0;
                                                $messages[] = sprintf(("You cannot leave '%s' blank!"),$ADMINCFG->config[$name]->name);
                                            }
                                            break;
                                case "access":
                                            if (!in_array($CFG->$name, array("PUBLIC","LOGGED_IN","PRIVATE"))) {
                                                $CFG->$name = "PRIVATE";
                                            }
                                            break;
                        
                    }
                }
                
                $PARSEDCFG->$name = addslashes($CFG->$name);
                
            }
            
            if ($oktosave) {
                
                $newconfigfile = elggadmin_configstring();
                if (!@file_put_contents($ADMINCFG->admin->elggdir . $ADMINCFG->admin->configfile, $newconfigfile)) {
                    $messages[] = sprintf(("We couldn't write your new configuration to your configuration file at %s. Therefore, please copy everything from the textbox below and paste it into a new file called %s in %s."),$ADMINCFG->admin->elggdir . $ADMINCFG->admin->configfile,$ADMINCFG->admin->configfile,$ADMINCFG->admin->elggdir . $ADMINCFG->admin->configfile) . "<br />" . "<textarea cols=\"40\" rows=\"6\">" . $newconfigfile . "</textarea>";
                } else {
                    $messages[] = ("Your new configuration was saved.");
                }
                
            }
            
            $_SESSION['messages'] = $messages;
            header("Location: index.php");
            exit;
            
        }
        
    // Run if Elgg's config.php doesn't exist.
        function elggadmin_config_create() {
        }
        
    // Generates a string containing all the configuration options
    // (Essentially a filled-in version of config-dist.php)
        function elggadmin_configstring() {
            
            global $CFG, $PARSEDCFG;
           
            include("config-template.php");
            
            $config = str_replace("\\\"","\"",$config);
            
            return $config;
            
        }
  
    // Initialises theme-related functions
        function elggadmin_theme_init() {
            
            global $CFG, $messages;
            
            // Determine whether or not we can write the theme back to disk
            if (!is_writable($CFG->templatesroot . "Default_Template/pageshell")
                || !is_writable($CFG->templatesroot . "Default_Template/css")) {                    
                    $messages[] = sprintf(("The administration panel can't write to the theme. You will need to edit your theme files directly or specify that %s is world-writable."),$CFG->templatesroot . "Default_Template/");
                }
                
            if (file_exists($CFG->dataroot) && 
                (!file_exists($CFG->dataroot . "backuppageshell") || !file_exists($CFG->dataroot . "backupcss"))
                ) {
                elggadmin_make_backup();
            }
                
        }
        
    // Initialises theme-related functions
        function elggadmin_frontpage_init() {
            
            global $CFG, $messages;
            
            // Determine whether or not we can write the theme back to disk
            if (!is_writable($CFG->templatesroot . "Default_Template/frontpage_loggedin")
                || !is_writable($CFG->templatesroot . "Default_Template/frontpage_loggedout")) {                    
                    $messages[] = sprintf(("The administration panel can't write to the front page template. You will need to edit your front page files directly or specify that %s is world-writable."),$CFG->templatesroot . "Default_Template/");
                }
                
        }
        
    // Takes a backup
        function elggadmin_make_backup() {
            
            global $CFG, $messages;
            
            if (!@copy($CFG->templatesroot . "Default_Template/pageshell", $CFG->dataroot . "backuppageshell")) {
                $messages[] = ("Could not backup pageshell.");
            }
            if (!@copy($CFG->templatesroot . "Default_Template/css", $CFG->dataroot . "backupcss")) {
                $messages[] = ("Could not backup css.");
            }
                    
        }
        
    // Restores from a backup
        function elggadmin_restore_backup() {
            
            global $CFG, $messages;
            
            if (!@copy($CFG->dataroot . "backuppageshell", $CFG->templatesroot . "Default_Template/pageshell")) {
                $messages[] = ("Could not restore pageshell backup.");
            }
            if (!@copy($CFG->dataroot . "backupcss", $CFG->templatesroot . "Default_Template/css")) {
                $messages[] = ("Could not restore css backup.");
            }
                    
        }
        
    // Save template
        function elggadmin_save_template() {
            
            global $CFG, $ADMINCFG, $messages;
            if (isset($_REQUEST['pageshell']) && isset($_REQUEST['css'])) {
  
                if (is_writable($CFG->templatesroot . "Default_Template/pageshell")
                    && is_writable($CFG->templatesroot . "Default_Template/css")) {
                              
                    $pageshell = trim($_REQUEST['pageshell']);
                    $css = trim($_REQUEST['css']);
                    
                    if(ini_get("magic_quotes_gpc")) {
                        $pageshell = stripslashes($pageshell);
                        $css = stripslashes($css);
                    }
                    
                    if (!@file_put_contents($CFG->templatesroot . "Default_Template/pageshell",$pageshell)) {
                        $messages[] = ("Could not save pageshell.");
                    } else {
                        $messages[] = ("Pageshell saved.");
                    }
                    if (!@file_put_contents($CFG->templatesroot . "Default_Template/css",$css)) {
                        $messages[] = ("Could not save CSS.");
                    } else {
                        $messages[] = ("CSS saved.");
                    }
                
                } else {
                    
                    $messages[] = sprintf(("The admin panel doesn't have the right to save to pageshell and css at %s. You may wish to speak to your system administrator about granting write access to those files."),$CFG->templatesroot . "Default_Template/");
                    
                }
                
                $_SESSION['messages'] = $messages;
                header("Location: theme.php");
                exit;
                
            }
            
        }
        
    // Save frontpage
        function elggadmin_save_frontpage() {
            
            global $CFG, $ADMINCFG, $messages;
            if (isset($_REQUEST['frontpage_loggedout']) && isset($_REQUEST['frontpage_loggedin'])) {
  
                if (is_writable($CFG->templatesroot . "Default_Template/frontpage_loggedout")
                    && is_writable($CFG->templatesroot . "Default_Template/frontpage_loggedin")) {
                              
                    $frontpage_loggedout = trim($_REQUEST['frontpage_loggedout']);
                    $frontpage_loggedin = trim($_REQUEST['frontpage_loggedin']);
                    
                    if(ini_get("magic_quotes_gpc")) {
                        $frontpage_loggedout = stripslashes($frontpage_loggedout);
                        $frontpage_loggedin = stripslashes($frontpage_loggedin);
                    }
                    
                    if (!@file_put_contents($CFG->templatesroot . "Default_Template/frontpage_loggedout",$frontpage_loggedout)) {
                        $messages[] = ("Could not save frontpage_loggedout.");
                    } else {
                        $messages[] = ("frontpage_loggedout saved.");
                    }
                    if (!@file_put_contents($CFG->templatesroot . "Default_Template/frontpage_loggedin",$frontpage_loggedin)) {
                        $messages[] = ("Could not save frontpage_loggedin.");
                    } else {
                        $messages[] = ("frontpage_loggedin saved.");
                    }
                
                } else {
                    
                    $messages[] = sprintf(("The admin panel doesn't have the right to save to frontpage_loggedout and frontpage_loggedin at %s. You may wish to speak to your system administrator about granting write access to those files."),$CFG->templatesroot . "Default_Template/");
                    
                }
                
                $_SESSION['messages'] = $messages;
                header("Location: frontpage.php");
                exit;
                
            }
            
        }
        
    // Displays the main form to edit themes
        function elggadmin_theme_main() {
            
            global $CFG, $messages;
            
            echo "<form action=\"\" method=\"post\">";
            
            echo "<p><b>" . ("Main pageshell") . "</b></p>";
            
            echo "<textarea name=\"pageshell\" >";
            readfile($CFG->templatesroot . "Default_Template/pageshell");
            echo "</textarea>";
            
            echo "<p><b>" . ("CSS styles") . "</b></p>";
            
            echo "<textarea name=\"css\" >";
            readfile($CFG->templatesroot . "Default_Template/css");
            echo "</textarea>";
            
            echo "<p>&nbsp;</p><p><i>" . ("Click below to save your settings.") . "</i></p>";
            echo "<p><input type=\"hidden\" value=\"theme:save\" name=\"action\" /><input type=\"submit\" value=\"" . ("Save") . "\" /></p>";
            echo "</form>";
            
            echo "<form action=\"\" method=\"post\">";
            echo "<p><i>" . ("Only press the button below if you're sure this theme works!") . "</i></p>";
            echo "<p><input type=\"hidden\" value=\"theme:backup:save\" name=\"action\" /><input type=\"submit\" value=\"" . ("Save a backup") . "\" /></p>";
            echo "</form>";
            
            if (file_exists($CFG->dataroot . "backuppageshell") && file_exists($CFG->dataroot . "backupcss")) {
                
                echo "<form action=\"\" method=\"post\">";
                echo "<p><i>" . ("Click to restore your last saved backup:") . "</i></p>";
                echo "<p><input type=\"hidden\" value=\"theme:backup:restore\" name=\"action\" /><input type=\"submit\" value=\"" . ("Restore from backup") . "\" /></p>";
                echo "</form>";
                
            }
            
        }
             
    // Displays the main form to edit front pages
        function elggadmin_frontpage_main() {
            
            global $CFG, $messages;
            
            echo "<form action=\"\" method=\"post\">";
            
            echo "<p><b>" . ("Front page (when logged out)") . "</b></p>";
            
            echo "<textarea name=\"frontpage_loggedout\" >";
            readfile($CFG->templatesroot . "Default_Template/frontpage_loggedout");
            echo "</textarea>";
            
            echo "<p><b>" . ("Front page (when logged in)") . "</b></p>";
            
            echo "<textarea name=\"frontpage_loggedin\" >";
            readfile($CFG->templatesroot . "Default_Template/frontpage_loggedin");
            echo "</textarea>";
            
            echo "<p>&nbsp;</p><p><i>" . ("Click below to save your settings.") . "</i></p>";
            echo "<p><input type=\"hidden\" value=\"frontpage:save\" name=\"action\" /><input type=\"submit\" value=\"" . ("Save") . "\" /></p>";
            echo "</form>";
            
            
        }
         
    // Checks to see if we're logged into the admin panel or not.
        function elggadmin_isloggedin() {
            
            global $CFG;
            if ($_SESSION['adminid'] != -1 
                && $_SESSION['admincode'] == md5($CFG->adminuser . $CFG->adminpassword) 
                && !empty($CFG->adminuser) && !empty($CFG->adminpassword) 
                && !empty($_SESSION['adminid'])) {
                    
                    return true;
                    
            }
            
            return false;
            
        }
        
    // Displays navigation
        function elggadmin_navigation($current_page) {
     
            $current[$current_page] = "class=\"active\"";
            
            echo <<< END
            <div id="navigation"><!-- start navigation -->
                <ul>
                    <li><a href="index.php" {$current['config']}>Site configuration</a></li>
                    <li><a href="theme.php" {$current['theme']}>Site theme</a></li>
                    <li><a href="frontpage.php" {$current['frontpage']}>Front page</a></li>
                    <!-- <li><a href="profile.php" {$current['profile']}>Profile fields</a></li>
                    <li><a href="plugins.php" {$current['plugins']}>Plugins</a></li> -->
                </ul>
            </div>
END;
            
        }
        
        function elggadmin_begin_content() {
            echo "<div id=\"content\">\n";
        }
        function elggadmin_end_content() {
            echo "</div>\n";
        }

    // Displays a login box, if admin logins have been defined in Elgg's config.php.
    // (Assumes, at this stage, that Elgg's config.php actually exists.)
        function elggadmin_loginbox() {
            
            global $CFG;
            
            if (!empty($CFG->adminuser) && !empty($CFG->adminpassword)) {
                
                echo "<h1>" . ("Please log in") . "</h1>";
                echo "<p>" . ("Log in with your admin username and password below.") . "</p>";
                echo "<form action=\"\" method=\"post\">\n";
                echo "<table border=\"0\">\n";
                echo "<tr><td><p>" . ("Username:") . "</p></td><td><p><input type=\"text\" name=\"adminuser\" value=\"\" /></p></td></tr>\n";
                echo "<tr><td><p>" . ("Password:") . "</p></td><td><p><input type=\"password\" name=\"adminpassword\" value=\"\" /></p></td></tr>\n";
                echo "<tr><td colspan=\"2\"><p><input type=\"hidden\" name=\"action\" value=\"admin:login\" /><input type=\"submit\" value=\"" . ("Log in") . "\" /></p></td></tr>\n";
                echo "</table>\n";
                
            } else {
                
                echo "<h1>" . ("Admin login not defined") . "</h1>";
                echo "<p>" . ("Before you use the admin panel, you will need to add the following code to Elgg's config.php:") . "</p>";
                echo "<textarea rows=\"4\" cols=\"40\">\n\n\$CFG->adminuser = '';\n\$CFG->adminpassword = '';</textarea>";
                echo "<p>" . ("The values for \$CFG->adminuser and \$CFG->adminpassword cannot be left blank.") . "</p>";
                
            }
            
        }
        
    // Displays a header for the admin panel
        function elggadmin_header() {
            
            global $messages;
            echo file_get_contents("HEADER");
            if (!empty($messages) && is_array($messages)) {
                echo "<div class=\"messages\"><ul>\n";
                foreach($messages as $message) {
                    
                    echo "<li>" . $message . "</li>\n";
                    
                }
                echo "</ul></div>\n";
            }
            
        }
        
    // Displays a footer for the admin panel
        function elggadmin_footer() {
            
            echo file_get_contents("FOOTER");
            
        }

?>