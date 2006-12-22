<?php
global $CFG;
global $db;
global $search_exclusions;

if (isset($parameter) && $parameter[0] == "weblog" || $parameter[0] == "weblogall") {
    
    $search_exclusions[] = "weblogall";
    $owner = optional_param('owner',0,PARAM_INT);
    $searchline = "tagtype = 'weblog' and tag = " . $db->qstr($parameter[1]) . "";
    $searchline = "(" . run("users:access_level_sql_where",$_SESSION['userid']) . ") and " . $searchline;
    $searchline = str_replace("access", "wp.access", $searchline);
    $searchline = str_replace("owner", "wp.weblog", $searchline);
    if ($refs = get_records_sql('SELECT wp.ident,wp.owner,wp.weblog,wp.ident,wp.title,u.name,t.ref FROM '.$CFG->prefix.'tags t
                                 JOIN '.$CFG->prefix.'weblog_posts wp ON wp.ident = t.ref
                                 JOIN '.$CFG->prefix.'users u ON u.ident = t.owner
                                 WHERE ('.$searchline.') ORDER BY wp.posted DESC LIMIT 50')) {
        foreach($refs as $post) {
            $run_result .= "\t<item>\n";
            $run_result .= "\t\t<title><![CDATA[" . __gettext("Weblog post") . " :: " . (stripslashes($post->name));
            if ($post->title != "") {
                $run_result .= " :: " . (stripslashes($post->title));
            }
            $weblogusername = user_info('username', $post->weblog);
            $run_result .= "]]></title>\n";
            $run_result .= "\t\t<link>" . url . $weblogusername . "/weblog/" . $post->ident . ".html</link>\n";
            $run_result .= "\t</item>\n";
        }
    }
    
}

?>