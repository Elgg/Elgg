<?php
// Action parser for profiles

global $page_owner;
$page_owner = (int) $page_owner;

if (isset($_POST['action']) && $_POST['action'] == "profile:edit" && logged_on && run("permissions:check", "profile")) {
    
    if (isset($_POST['profiledetails'])) {
        delete_records('profile_data','owner',$page_owner);
        foreach($_POST['profiledetails'] as $field => $value) {
            
            $value = trim($value);
            
            if ($value != "") {
                //TODO get rid of variable duplication here. (Penny)
                $access = trim($_POST['profileaccess'][$field]);
                $owner = (int) $page_owner;
                
                $pd = new StdClass;
                $pd->name = $field;
                $pd->value = $value;
                $pd->access = $access;
                $pd->owner = $page_owner;
                // $pd = plugin_hook("profile_item","update",$pd);
                $insert_id = insert_record('profile_data',$pd);
                $pd->ident = $insert_id;
                $pd = plugin_hook("profile_item","publish",$pd);
                
                foreach($data['profile:details'] as $datatype) {
                    if ($datatype[1] == $field && $datatype[2] == "keywords") {
                        delete_records('tags', 'tagtype', $field, 'owner', $page_owner);
                        $value = insert_tags_from_string ($value, $field, $insert_id, $access, $page_owner);
                    }
                }
                
            }
            
        }
        $messages[] = __gettext("Profile updated.");
    }

}

?>