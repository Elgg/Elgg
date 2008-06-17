<?php
global $CFG;
global $folder;
global $page_owner;

$file_id = optional_param('edit_file_id',0,PARAM_INT);
if (!empty($file_id)) {
    $file = get_record_sql('SELECT f.*,u.username FROM '.$CFG->prefix.'files f 
                                    JOIN '.$CFG->prefix.'users u ON u.ident = f.owner 
                                    WHERE f.ident = ?',array($file_id));
    if (!empty($file)  && (permissions_check("files:edit",$file->owner) || permissions_check("files:edit",$file->files_owner))) {
        $page_owner = $file->files_owner;
        
        $description = stripslashes($file->description);
        $title = htmlspecialchars(stripslashes($file->title), ENT_COMPAT, 'utf-8');
        
        $fileLabel = __gettext("File title:"); // gettext variable
        $body = <<< END
            <form action="{$CFG->wwwroot}mod/file/action_redirection.php" method="post">
            <div id="edit_files">
            <table width="80%">
                <tr>
                    <td width="30%">    
                        <h4><label for="edit_file_title">$fileLabel</label></h4>
                    </td>
                    <td width="70%">
END;
        $body .= display_input_field(array("edit_file_title",$title,"text"));
        $fileDesc = __gettext("File description:"); // gettext variable
        $body .= <<< END
                    </td>
                </tr>
                <tr>
                    <td width="30%">
                        <h4><label for="edit_file_description">$fileDesc</label></h4>
                    </td>
                    <td width="70%">
END;
        $body .= display_input_field(array("edit_file_description",$description,"longtext"));
        $fileAccess = __gettext("Access restrictions:"); // gettext variable
        $body .= <<< END
                    </td>
                </tr>
                <tr>
                    <td width="30%">
                        <h4><label for="edit_file_access">$fileAccess</label></h4>
                    </td>
                    <td width="70%">
END;
        $body .= run("display:access_level_select",array("edit_file_access",$file->access));
        $fileFolder = __gettext("File folder:"); // gettext variable
        $body .= <<< END
                    </td>
                </tr>
                <tr>
                    <td width="30%">
                        <h4><label for="edit_file_folder">$fileFolder</label></h4>
                    </td>
                    <td width="70%">
END;
        $body .= run("folder:select", array("edit_file_folder",$file->files_owner,$file->folder));
        $keywords = __gettext("Keywords (comma separated):"); // gettext variable
        $body .= <<< END
                    </td>
                </tr>
                <tr>
                    <td width="30%">
                        <h4><label for="edit_file_keywords">$keywords</label></h4>
                    </td>
                    <td width="70%">
END;
        $body .= display_input_field(array("edit_file_keywords","","keywords","file",$file_id));
        $body .= <<< END
                    </td>
                </tr>
END;
    
        $body .= run("metadata:edit",$file_id);
        
        $saveChanges = __gettext("Save changes"); // gettext variable
        $body .= <<< END
                
                <tr>
                    <td colspan="2" align="center"><br />
                        <input type="hidden" name="folder" value="{$folder}" />
                        <input type="hidden" name="file_id" value="{$file_id}" />
                        <input type="hidden" name="action" value="files:editfile" />
                        <input type="submit" value=$saveChanges />
                    </td>
                </tr>
    
            </table>
                </div>
END;
    
        $run_result .= templates_draw(array(
                                            'context' => 'databoxvertical',
                                            'name' => __gettext("Edit ") . $title,
                                            'contents' => $body
                                            )
                                      );
        
        $run_result .= <<< END
        </form>
END;
    } else {
        echo "?";
    }
}

?>