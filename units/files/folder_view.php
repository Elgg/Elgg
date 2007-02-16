<?php

    if (isset($parameter) && $parameter != "") {
        $folder = (int) $parameter;
    } else {
        $folder = -1;
    }
    if ($folder != -1) {
        $folder_object = get_record('file_folders','ident',$folder,'files_owner',$page_owner);
    } else {
        $folder_object = new stdClass();
        $folder_object->ident = -1;
        $folder_object->handler = "elgg";
        $folder_object->name = __gettext("Root Folder");
    }
    
    $run_result .= file_folder_view($folder_object);
        
?>
