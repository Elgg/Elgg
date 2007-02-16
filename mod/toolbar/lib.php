<?php

    function toolbar_pagesetup() {
        global $CFG, $metatags;
        require_once($CFG->dirroot.'lib/filelib.php'); // to ensure file_get_contents()
        $css = file_get_contents($CFG->dirroot . "mod/toolbar/css");
        $css = str_replace("{{url}}", $CFG->wwwroot, $css);
        $metatags .= $css;
    }
    
    function toolbar_init() {
        global $CFG, $template;
        $CFG->templates->variables_substitute['toolbar'][] = "toolbar_mainbody";
        $CFG->templates->variables_substitute['searchbox'][] = "toolbar_searchbox";
    }
    
    function toolbar_mainbody($vars) {
        
        global $CFG;
        require_once($CFG->dirroot.'lib/filelib.php'); // to ensure file_get_contents()
        if (isloggedin()) {
            $toolbar = file_get_contents($CFG->dirroot . "mod/toolbar/toolbar.inc");
        } else {
            $toolbar = file_get_contents($CFG->dirroot . "mod/toolbar/toolbarloggedout.inc");
        }
        $toolbar = str_replace("{{url}}", $CFG->wwwroot, $toolbar);
        $toolbar = str_replace("{{menu}}", templates_variables_substitute(array(array(),"menu")), $toolbar);
        $toolbar = str_replace("{{topmenu}}", templates_variables_substitute(array(array(),"topmenu")), $toolbar);
        $toolbar = str_replace("{{logon}}", __gettext("Log on:"), $toolbar);
        $toolbar = str_replace("{{username}}", __gettext("Username"), $toolbar);
        $toolbar = str_replace("{{password}}", __gettext("Password"), $toolbar);
        $toolbar = str_replace("{{poweredby}}", __gettext("Powered by Elgg"), $toolbar);
        $toolbar = str_replace("{{remember}}", __gettext("Remember me"), $toolbar);
        
        return $toolbar;
        
    }
    
    function toolbar_searchbox($vars) {
        
        global $CFG;
        $all = __gettext("all");
        $people = __gettext("People");
        $communities = __gettext("Communities");
        $tagcloud = __gettext("Tag cloud");
        $browse = __gettext("Browse");
        
        $searchbox = <<< END
        
        <div id="search-header"><!-- open search-header div -->
        <form id="searchform" action="{$CFG->wwwroot}search/index.php" method="get">
            <p><input type="text" size="20" name="tag" value="search" />
            <select name="user_type">
                <option value="">-- {$all} --</option>
                <option value="person">{$people}</option>
                <option value="community">{$communities}</option>
            </select>
            <input type="submit" value="Go" /><span><br />[<a href="{$CFG->wwwroot}mod/browser/"><b>{$browse}</b></a>] [<a href="{$CFG->wwwroot}search/tags.php"><b>{$tagcloud}</b></a>]</span></p>
        </form>
        </div><!-- close search-header div -->
        
END;

        return $searchbox;
        
    }

?>