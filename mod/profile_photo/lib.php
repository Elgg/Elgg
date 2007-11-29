<?php

    function profile_photo_pagesetup() {
    }

    function profile_photo_init() {
        global $CFG;
        $CFG->display_field_module['profile_photo'] = "profile_photo";
        listen_for_event("user","publish","profile_photo_user_publish");
    }

    function profile_photo_user_publish($object_type, $event, $object) {
        global $data;
        if ($object_type == "user" && $event == "publish" && is_array($data['profile:details']) && !empty($data['profile:details'])) {
            foreach($data['profile:details'] as $profileitem) {
                if ($profileitem->field_type == "profile_photo") {
                    $profile_data = new stdClass;
                    $profile_data->access = "PUBLIC";
                    $profile_data->owner = $object->ident;
                    $profile_data->name = $profileitem->internal_name;
                    $profile_data->value = "photo";
                }
            }
        }

	return $object;
    }
    
    function profile_photo_display_input_field($parameter) {
        global $CFG;
        $html = "";
        if ($parameter[2] == "profile_photo") {
            $cleanid = ereg_replace("[^A-Za-z0-9_:\\.-]", "__", $parameter[0]);
            $file = get_record('profile_data','ident',$parameter[4]);
            if (!$file || $file->value == "photo") {
                unset($parameter[1]);
            }
            if (!empty($parameter[1])) {
                $html .= "<img src=\"{$CFG->wwwroot}mod/profile_photo/img.php?id={$parameter[4]}&amp;constraint1=w&amp;size1=250&amp;constraint2=h&amp;size2=200\" alt=\"Profile photo\" /><br />";
                $html .= "<label for=\"$cleanid\"><input name=\"".$parameter[0]."\" type=\"checkbox\" id=\"$cleanid\" value=\"photo\" />";
                $html .= __gettext("Click here to remove this photo.");
                $html .= "</label>";
            } else {
                $html .= "<input name=\"profile_photo_".$parameter[3]."\" type=\"file\" /><br />";
                $html .= "<label for=\"$cleanid\"><input name=\"".$parameter[0]."\" type=\"checkbox\" id=\"$cleanid\" value=\"photo\" />";
                $html .= __gettext("Click here to verify that this is a photo of you and that it is not obscene or abusive.");
                $html .= "</label>";
            }
        }
        return $html;
    }

    function profile_photo_display_output_field($parameter) {
        global $CFG, $profile_id;
        $html = '';
        if ($parameter[1] == "profile_photo") {
            if (!empty($parameter[4]) && $parameter[0] != "photo") {
                $html .= "<img class=\"profile-photo\" src=\"{$CFG->wwwroot}mod/profile_photo/img.php?id={$parameter[4]}&amp;constraint1=w&amp;size1=250\" alt=\"Profile photo\" />";
            } else {
                $pictureglyph = __gettext("Click here to upload a photo");
                $html = <<< END
</p>
<style type="text/css">
div#default-profile-icon {
    width:250px;
    height:200px;
    background:url({$CFG->wwwroot}mod/profile_photo/default.gif) no-repeat;
    position:relative;
}

div#default-profile-icon p {
    position:absolute;
    top:150px;
    right:40px;
    margin:0;
    padding:0;
    color:#fff;
}

div#default-profile-icon p a {
    text-decoration:underline;
    color:#fff;
}
</style>

<div id="default-profile-icon">
<p><a href="{$CFG->wwwroot}profile/edit.php?profile_id={$profile_id}">{$pictureglyph}</a></p>
</div>
<p>
END;
                
            }
        }
        return $html;
    }
    
    function profile_photo_validate_input_field($parameter) {
        global $CFG, $messages, $data, $profile_id;
        
            $found = false;
            
            foreach($data['profile:details'] as $profileitem) {
                if (is_array($profileitem)) {
                    $fname = $profileitem[1];
                    $ftype = $profileitem[2];
                } else {
                    $fname = $profileitem->internal_name;
                    $ftype = $profileitem->field_type;
                }
                if ($fname == $parameter->name) {
                    $found = true;
                    break;
                }
            }
            
            if ($found && $ftype = "profile_photo") {
                require_once($CFG->dirroot.'lib/uploadlib.php');
                require_once($CFG->dirroot.'lib/filelib.php');
                $textlib = textlib_get_instance();
                $upload_folder = $textlib->substr(user_info("username", $profile_id),0,1);
                $um = new upload_manager('profile_photo_'.$fname,true,true,false,5000000,true);
                $reldir =  "profile_photos/" . $upload_folder . "/" . user_info("username", $profile_id) . "/" . $parameter->name . "/"; 
                $dir = $CFG->dataroot .$reldir;
                if ($um->process_file_uploads($dir)) {
                    $parameter->value = $reldir . $um->get_new_filename();
                    update_record('profile_data',$parameter);
                } else {
                    $messages[] = $um->get_errors();
                }
            }

        return true;
    }

?>
