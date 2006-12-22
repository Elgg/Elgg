<?php
global $CFG;
global $db;
global $search_exclusions;

if (isset($parameter) && $parameter[0] == "file") {
    
    $search_exclusions[] = "folder";
    $search_exclusions[] = "file";
    $dbtag = $db->qstr($parameter[1]);
    
    $owner = optional_param('owner',0,PARAM_INT);
    $accessline = "(" . run("users:access_level_sql_where",$_SESSION['userid']) . ")";
    $searchline_files = "$accessline and tagtype = 'file' and tag = " . $dbtag . "";
    $searchline_folders = "$accessline and tagtype = 'folder' and tag = " . $dbtag . "";
    $searchline_files = str_replace("access", "f.access", $searchline_files);
    $searchline_files = str_replace("owner", "f.owner", $searchline_files);
    $searchline_folders = str_replace("access", "ff.access", $searchline_folders);
    $searchline_folders = str_replace("owner", "ff.owner", $searchline_folders);

    $file_refs = get_records_sql('SELECT f.*,u.username,u.name AS fullname,t.ref FROM '.$CFG->prefix.'tags t 
                                  JOIN '.$CFG->prefix.'files f ON f.ident = t.ref
                                  JOIN '.$CFG->prefix.'users u ON u.ident = t.owner
                                  WHERE '.$searchline_files.' LIMIT 50');

    $folder_refs = get_records_sql('SELECT ff.*, u.username, u.name AS fullname, t.ref FROM '.$CFG->prefix.'tags t 
                                   JOIN '.$CFG->prefix.'file_folders ff ON ff.ident = t.ref
                                   JOIN '.$CFG->prefix.'users u ON u.ident = t.owner 
                                   WHERE '.$searchline_folders.' LIMIT 50');
    $searchline = "";
    if (!empty($folder_refs)) {
        foreach($folder_refs as $folder) {
            $run_result .= "\t<item>\n";
            $run_result .= "\t\t<title><![CDATA[". __gettext("File folder") ." :: " . stripslashes($folder->fullname) . " :: " . stripslashes($folder->name) . "]]></title>\n";
            $run_result .= "\t\t<link>" . url  . $folder->username . "/files/" . $folder->ident . "</link>\n";
            $run_result .= "\t</item>\n";
        }
    }
    if (!empty($file_refs)) {
        require_once($CFG->dirroot.'lib/filelib.php');
        foreach($file_refs as $file) {
            $mimetype = mimeinfo('type',$file->location);
            $run_result .= "\t<item>\n";
            $run_result .= "\t\t<title><![CDATA[". __gettext("File") ." :: " . stripslashes($file->fullname) . " :: " . stripslashes($file->title) . "]]></title>\n";
            $run_result .= "\t\t<link>" . url  . $file->username . "/files/" . $file->folder . "/" . $file->ident . "/" . urlencode(stripslashes($file->originalname)) . "</link>\n";
            $run_result .= "\t\t<enclosure url=\"" . url  . $file->username . "/files/" . $file->folder . "/" . $file->ident . "/" .urlencode(htmlspecialchars(stripslashes($file->originalname), ENT_COMPAT, 'utf-8')) . "\" length=\"". $file->size ."\" mimetype=\"$mimetype\" />\n";
            $run_result .= "\t</item>\n";
        }
    }
}

?>