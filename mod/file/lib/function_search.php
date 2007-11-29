<?php
global $CFG,$USER,$PAGE;
global $db;
global $search_exclusions;


$handle = 1;

if (!empty($PAGE->returned_items) && $PAGE->returned_items == "accounts") {
    $handle = 0;
}

if (isset($parameter) && $parameter[0] == "file" && $handle) {
    
    $dbtag = $db->qstr($parameter[1]);
    
    $search_exclusions[] = "folder";
    $owner = optional_param('owner',0,PARAM_INT);
    $accessline = "(" . run("users:access_level_sql_where",$USER->ident) . ")";
    $accessline = str_replace("owner","t.owner",$accessline);
    $searchline_files = "$accessline AND tagtype = 'file' AND owner = $owner AND tag = " . $dbtag . "";
    $searchline_folders = "$accessline AND tagtype = 'folder' AND owner = $owner AND tag = " . $dbtag . "";
    $searchline = "";
    $searchlist = array();
    $file_refs = get_records_select('tags t',$searchline_files);
    $folder_refs = get_records_select('tags t',$searchline_folders);
    if (!empty($folder_refs) && sizeof($folder_refs) > 0) {
        foreach($folder_refs as $folder) {
            $searchlist[] = $folder->ref;
        }
        $searchline = " ff.ident IN (" . implode(", ", $searchlist) . ") ";
        if (!empty($PAGE->search_type_unformatted)) {
            $searchline .= " AND u.user_type = " . $PAGE->search_type;
        }
        $folders = get_records_sql('SELECT ff.name,u.name AS userfullname,u.ident as userid,u.username,ff.ident 
                                    FROM '.$CFG->prefix.'file_folders ff
                                    LEFT JOIN '.$CFG->prefix.'users u ON u.ident = ff.files_owner
                                    WHERE ('.$searchline.') ORDER BY name ASC');
        
        $name = '';
        if (is_array($folders) && count($folders) > 0) {
            $keys = array_keys($folders);
            $f = $folders[$keys[0]];
            if (!empty($f)) {
                // $name = stripslashes($f->userfullname);
                $name = run("profile:display:name",$f->userid);
            }
            
            $run_result .= "<h2>" . sprintf(__gettext("Folders owned by '%s' in category '%s'"),$name,$parameter[1])."</h2>\n";
            foreach($folders as $folder) {
                $run_result .= templates_draw(array(
                                                    'context' => 'folder',
                                                    'username' => $folder->username,
                                                    'url' => url . $folder->username . "/files/" . $folder->ident,
                                                    'ident' => $folder->ident,
                                                    'name' => stripslashes($folder->name),
                                                    'menu' => "",
                                                    'icon' => url . "mod/file/folder.png",
                                                    'keywords' => ""
                                                    )
                                              );
            }
        }
    }
    $searchline = "";
    $searchlist = array();
    if (!empty($file_refs) && sizeof($file_refs) > 0) {
        foreach($file_refs as $file) {
            $searchlist[] = $file->ref;
        }
        $searchline = " f.ident IN (" . implode(", ", $searchlist) . ") ";
        $files = get_records_sql('SELECT f.*,u.username,u.name AS userfullname,u.ident as userid 
                                  FROM '.$CFG->prefix.'files f 
                                  LEFT JOIN '.$CFG->prefix.'users u ON u.ident = f.owner 
                                  WHERE ('.$searchline.') ORDER BY title ASC');
        $name = '';
        $username = '';

        if (!empty($files)) {
            $keys = array_keys($files);
            $f = $files[$keys[0]];
            if (!empty($f)) {
                // $name = stripslashes($f->userfullname);
                $name = run("profile:display:name",$f->userid);
                $username = $f->username;
            }
            $folder_names = array();
            
            $run_result .= "<h2>" . sprintf(__gettext("Files owned by %s in category '%s'"), $name, $parameter[1])."</h2>\n";
            foreach($files as $file) {
                if (!isset($folder_names[$file->folder])) {
                    $folder_name[$file->folder] = get_field("file_folders","name","ident",$file->folder);
                }
                $menu = '[<a href="' . $CFG->wwwroot . $file->username . "/files/" . (($file->folder > 0) ? $file->folder . '/' : '') . '">' . sprintf(__gettext("In folder '%s'"), $folder_name[$file->folder]) . '</a>]';
                $run_result .= templates_draw(array(
                                                    'context' => 'file',
                                                    'username' => $file->username,
                                                    'title' => stripslashes($file->title),
                                                    'ident' => $file->ident,
                                                    'folder' => $file->folder,
                                                    'description' => stripslashes($file->description),
                                                    'originalname' => stripslashes($file->originalname),
                                                    'url' => url.$file->username."/files/".$file->folder."/".$file->ident."/".$file->originalname,
                                                    'menu' => $menu,
                                                    'icon' => url."mod/file/file.png",
                                                    'keywords' => ""
                                                    )
                                              );
            }
            $run_result .= "<p><small>[ <a href=\"".url.$username . "/files/rss/" . $parameter[1] . "\">"
                .sprintf(__gettext("RSS feed for files owned by %s"), $name) . " in category '".$parameter[1]."'</a> ]</small></p>\n";
        }
    }
    $searchline = " tagtype IN ('file','folder') AND tag = " . $dbtag . "";
    $searchline = "(" . run("users:access_level_sql_where",$_SESSION['userid']) . ") and " . $searchline;
    $searchline = str_replace("owner","t.owner",$searchline);
    $sql = 'SELECT DISTINCT u.* FROM '.$CFG->prefix.'tags t JOIN '.$CFG->prefix.'users u ON u.ident = t.owner WHERE ('.$searchline.')';
    if ($parameter[0] == "file") {
        $sql .= " AND u.ident != " . $owner;
    }
    if ($users = get_records_sql($sql)) {
        $run_result .= "<h2>". __gettext("Users and communities with files or folders in category") . " '". $parameter[1]."'</h2>\n";
        $body = "<table><tr>";
        $i = 1;
        $w = 100;
        if (sizeof($users) > 4) {
            $w = 50;
        }
        foreach($users as $key => $info) {
            $friends_userid = $info->ident;
            $friends_name = user_name($info->ident);
            $info->icon = run("icons:get",$info->ident);
            $friends_menu = run("users:infobox:menu",array($info->ident));
            $link_keyword = urlencode($parameter[1]);
            $friends_icon = user_icon_html($info->ident,$w);
            $body .= <<< END
        <td align="center">
            <p>
            <a href="{$CFG->wwwroot}search/index.php?file={$link_keyword}&amp;owner={$friends_userid}">
            {$friends_icon}</a><br />
            <span class="userdetails">
                {$friends_name}
                {$friends_menu}
            </span>
            </p>
        </td>
END;
            if ($i % 5 == 0) {
                $body .= "\n</tr><tr>\n";
            }
            $i++;
        }
        $body .= "</tr></table>";
        $run_result .= $body;
    }
}

?>