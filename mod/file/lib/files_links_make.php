<?php
global $CFG;
// Turn file ID into a proper link

if (isset($parameter)) {
    run("files:metadata:init");
    $fileid = (int) $parameter;
    if ($file = get_record('files','ident',$fileid)) {
        
        require_once($CFG->dirroot . 'lib/filelib.php');
        $filelocation = file_cache($file);
        if (run("users:access_level_check",$file->access) || $file->owner == $_SESSION['userid']) {
            if (!in_array(run("files:mimetype:inline",$filelocation), $data['mimetype:inline'])) {
                require_once($CFG->dirroot.'lib/filelib.php');
                $mimeinfo = mimeinfo('type',$file->location);
                $filepath = $CFG->wwwroot . user_info('username', $file->owner) . "/files/" . $file->folder . "/" . $file->ident . "/" . urlencode($file->originalname);
                switch($mimeinfo) {
                    
                    case "audio/mpeg":
                    case "audio/mp3":
                        $filetitle = urlencode(stripslashes($file->title));
                        $run_result .= "
        <embed src=\"" . $CFG->wwwroot . "mod/file/mp3player/xspf_player_slim.swf?song_url=$filepath&amp;song_title=$filetitle\"
        type=\"application/x-shockwave-flash\"
        height=\"15\" width=\"400\" />";
                        break;
                        
                   case "application/x-shockwave-flash":
                        $run_result .= "<embed src=\"$filepath\" type=\"application/x-shockwave-flash\" />";
                        break;
                   default:
                        $extension = strtolower(substr($file->originalname,strpos($file->originalname,".")+1));
                        $type=(array_key_exists($extension,get_mimetype_array()))?" $extension":"";
                        $run_result .= '<a class="mediafile'.$type.'" href="' . $filepath . '">';
                        $run_result .= stripslashes((!empty($file->title))?$file->title:$file->originalname);
                        $run_result .= "</a> ";
                        break;
                   
                }
            } else {
                list($width, $height, $type, $attr) = getimagesize($filelocation);
                if ($width > 400 || $height > 400) {
                    $run_result .= "<a href=\"";
                    $run_result .= $CFG->wwwroot . user_info('username', $file->owner) . "/files/" . $file->folder . "/" . $file->ident . "/" . $file->originalname;
                    $run_result .= "\" >";
                    $run_result .= '<img src="' . $CFG->wwwroot . '_icon/file/' . $file->ident . '" alt="' . htmlspecialchars(stripslashes($file->title), ENT_COMPAT, 'utf-8') . '" />';
                    $run_result .= "</a>";
                } else {
                    $run_result .= "<img src=\"";
                    $run_result .= url . user_info('username', $file->owner) . "/files/" . $file->folder . "/" . $file->ident . "/" . $file->originalname;
                    $run_result .= "\" $attr alt=\"".htmlspecialchars(stripslashes($file->title), ENT_COMPAT, 'utf-8')."\" />";
                }
            }
        } else {
            $run_result .= "<b>[" . __gettext("You do not have permission to access this file") . "]</b>";
        }
    } else {
        $run_result .= "<b>[" . __gettext("File does not exist") . "]</b>";
    }
}

?>