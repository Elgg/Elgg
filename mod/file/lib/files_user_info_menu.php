<?php
global $USER;
global $CFG;
global $page_owner;
$profile_id = $page_owner;

if ($page_owner != -1 && $page_owner != $USER->ident) {
    $posts = count_records_select('files',"files_owner = $profile_id AND (".run("users:access_level_sql_where",$profile_id).")");
    if ($USER->ident == $profile_id) {
        $title = __gettext("Your Files");
    } else {
        $title = __gettext("Files");
    }
    
    if ($posts == 1) {
        $filesstring = $posts . " " . __gettext("file");
    } else {
        $filesstring = $posts . " " . __gettext("files");
    }
    
    $weblog_username = user_info('username', $profile_id);
    $fileStorage = __gettext("File Storage"); // gettext variable
    $body = <<< END
        <ul>
            <li><a href="{$CFG->wwwroot}{$weblog_username}/files/">$fileStorage</a> <a href="{$CFG->wwwroot}{$weblog_username}/files/rss/"><img src="{$CFG->wwwroot}mod/template/icons/rss.png" alt="RSS" border="0" /></a><br />
                ({$filesstring})</li>
        </ul>
END;

    $run_result .= "<li id=\"sidebar_files\">";
    $run_result .= templates_draw(array(
                                        'context' => 'sidebarholder',
                                        'title' => $title,
                                        'body' => $body
                                        )
                                  );
    $run_result .= "</li>";
}
        
?>