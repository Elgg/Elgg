<?php

    function photogallery_pagesetup() {
    }
    
    function photogallery_init() {
        
        global $CFG;
        $CFG->folders->handler["photogallery"]['menuitem'] = __gettext("Photo gallery");
        $CFG->folders->handler["photogallery"]['view'] = "photogallery_folder_view";
        $CFG->folders->handler["photogallery"]['preview'] = "photogallery_folder_preview";
        
    }
    
    function photogallery_folder_view($folder) {
        
        global $CFG, $metatags, $messages;
        
        require_once($CFG->dirroot.'lib/filelib.php');
        $metatags .= file_get_contents($CFG->dirroot . "mod/photogallery/css");
        $metatags .= <<< END
        <script type="text/javascript">
            var elggWwwRoot = "{$CFG->wwwroot}";
        </script>
        <script type="text/javascript" src="{$CFG->wwwroot}mod/photogallery/lightbox/js/prototype.js"></script>
        <script type="text/javascript" src="{$CFG->wwwroot}mod/photogallery/lightbox/js/scriptaculous.js?load=effects"></script>
        <script type="text/javascript" src="{$CFG->wwwroot}mod/photogallery/lightbox/js/lightbox.js"></script>
        <link rel="stylesheet" href="{$CFG->wwwroot}mod/photogallery/lightbox/css/lightbox.css" type="text/css" media="screen" />
        
END;
        
        $file_html = "";
        $photo_html = "";
        $folder_html = "";
        
        
        
        // Get all the files in this folder
            if ($files = get_records_select('files',"folder = ? AND files_owner = ? ORDER BY time_uploaded desc",array($folder->ident,$folder->files_owner))) {
                
                foreach($files as $file) {
                    
                    if (run("users:access_level_check",$file->access) == true) {
                    
                        $image = $CFG->wwwroot . "_files/icon.php?id=" . $file->ident . "&amp;w=200&amp;h=200";
                        $filepath = $CFG->wwwroot . user_info("username",$file->files_owner) . "/files/$folder->ident/$file->ident/" . urlencode($file->originalname);
                        $image = "<a href=\"{$CFG->wwwroot}_files/icon.php?id={$file->ident}&w=500&h=500\" rel=\"lightbox[folder]\"><img src=\"$image\" /></a>";
                        $fileinfo = round(($file->size / 1048576),4) . "Mb";
                        $filelinks = file_edit_links($file);
                        $uploaded = sprintf(__gettext("Uploaded on %s"),strftime("%A, %d %B %Y",$file->time_uploaded));
                        $keywords = display_output_field(array("","keywords","file","file",$file->ident,$file->owner));
                        $mimetype = mimeinfo('type',$file->originalname);
    
                        if (empty($file->title)) {
                            $file->title = __gettext("No title");
                        }
                        
                        if (substr_count($mimetype, "image") > 0) {
                            $photo_html .= <<< END
                            
                            <div class="photogallery-photo-container">
                                <div class="photogallery-photo-image">
                                    $image
                                </div>
                                <div class="photogallery-photo-info">
                                    <h2 class="photogallery-photo-title"><a href="$filepath" >{$file->title}</a></h2>
                                    <p class="photogallery-photo-description">
                                        {$file->description}
                                    </p>
                                    <p class="photogallery-photo-keywords">
                                        {$keywords}
                                    </p>
                                    <p class="photogallery-photo-infobar">
                                        {$uploaded}<br />
                                        {$fileinfo} {$mimetype} {$filelinks}
                                    </p>
                                </div>
                            </div>
                            
END;
                        } else {
                            $file_html .= <<< END
                            
                            <div class="photogallery-file-container">
                                <div class="photogallery-file-image">
                                    <a href="{$filepath}">$image</a>
                                </div>
                                <div class="photogallery-file-info">
                                    <h2 class="photogallery-file-title"><a href="{$filepath}">{$file->title}</a></h2>
                                    <p>{$file->description}</p>
                                    <p class="photogallery-file-keywords">
                                        {$keywords}
                                    </p>
                                    <p class="photogallery-file-infobar">
                                        {$uploaded}<br />
                                        {$fileinfo} {$mimetype} {$filelinks}
                                    </p>
                                </div>
                            </div>
                            
END;
                        }
                    }
                }
                
            }
            
            if ($subfolders = get_records_select('file_folders',"parent = ? AND files_owner = ? ORDER BY name desc",array($folder->ident,$folder->owner))) {
                foreach($subfolders as $subfolder) {
                    $folderlinks = file_folder_edit_links($subfolder);
                    $keywords = display_output_field(array("","keywords","folder","folder",$subfolder->ident,$subfolder->owner));
                    $filepath = $CFG->wwwroot . user_info("username",$folder->files_owner) . "/files/" . $subfolder->ident;
                    $folder_html .= <<< END
                    
                        <div class="photogallery-file-container">
                            <div class="photogallery-file-image">
                                <a href="{$filepath}"><img src="{$CFG->wwwroot}_files/folder.png" /></a>
                            </div>
                            <div class="photogallery-file-info">
                                <h2 class="photogallery-file-title"><a href="{$filepath}">{$subfolder->name}</a></h2>
                                <p class="photogallery-file-keywords">
                                    {$keywords}
                                </p>
                                <p class="photogallery-file-infobar">
                                    {$folderlinks}
                                </p>
                            </div>
                        </div>
                    
END;
                }
            }
            
            if (!empty($file_html)) {
                $file_html = "<h2>" . __gettext("Non-photo files") . "</h2>" . $file_html;
            }
            
            if (!empty($folder_html)) {
                $folder_html = "<h2>" . __gettext("Subfolders") . "</h2>" . $folder_html;
            }
            
            $body = $photo_html . $file_html . $folder_html;
            if (empty($body)) {
                $body = "<p>" . __gettext("This folder is currently empty.") . "</p>";
            }
            
            return $body;
        
    }
    
    function photogallery_folder_preview($folder) {
    }

?>