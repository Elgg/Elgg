<?php
/*
 * Created on Apr 15, 2007
 */
 global $metatags,$CFG;
 require_once(dirname(dirname(__FILE__))."/../includes.php");
 require_once($CFG->dirroot . 'lib/filelib.php');

 templates_page_setup();

 $setall = optional_param("setall");
 if (empty($setall)) {
    $function_name = "insertFile";
 } else {
     $function_name = "insertFileBrutal";
 }
 
 $url= substr($CFG->wwwroot, 0, -1);
 $metatags .= "<script language=\"javascript\" type=\"text/javascript\" src=\"$url/mod/contenttoolbar/js/helpers.js\"></script>";
 $metatags .= "<script language=\"javascript\" type=\"text/javascript\" src=\"$url/mod/contenttoolbar/js/script.js\"></script>";
 $metatags .= "<script language=\"javascript\" type=\"text/javascript\" src=\"$url/mod/contenttoolbar/js/edit.js\"></script>";
 $metatags .= "<script language=\"javascript\" type=\"text/javascript\" src=\"$url/mod/file/file-wizard.js\"></script>";
 $metatags .= "<link rel=\"stylesheet\" href=\"" . $CFG->wwwroot . "mod/contenttoolbar/wizard.css\" type=\"text/css\" media=\"screen\" />";
 $metatags .= "<link rel=\"stylesheet\" href=\"" . $CFG->wwwroot . "mod/file/file-wizard.css\" type=\"text/css\" media=\"screen\" />";
 $metatags .= "<style type=\"text/css\">";
 $metatags .= str_replace("{{url}}", $CFG->wwwroot, file_get_contents(dirname(__FILE__). "/file-icons.css"));
 $metatags .= "</style>";
 
 $file_name = $USER->username;
 run("profile:init");
 run("files:init");

 $field = optional_param('input_field','new_weblog_post');
 
 $folder_id = $folder;
 $user_folders = get_records('file_folders','files_owner',$owner);
 $folder_object = get_record('file_folders','files_owner',$owner,'ident',$folder);
 
 $keepopen = __gettext("Keep this window open");
 // Folders list
 $folders = array();
 if (!empty($user_folders)) {
     foreach ($user_folders as $folder){
      $folders[$folder->parent][$folder->ident]="$folder->name";
     }
 }
 $directories = '';
 $keys = array_keys($folders);
 for($i=0;$i<count($keys);$i++){
   if(is_array($folders[$keys[$i]])){
     foreach($folders[$keys[$i]] as $ident=>$folder){
       $directories.="<li><a href=\"".$CFG->wwwroot."mod/file/file_include_wizard.php?owner=$owner&folder=$ident&input_field=$field\">$folder</a>";
       if(array_key_exists($ident,$folders)){
          $directories.="<ul>\n"; 
          foreach($folders[$ident] as $_ident => $_folder){
            $directories.="<li><a href=\"".$CFG->wwwroot."mod/file/file_include_wizard.php?owner=$owner&folder=$_ident&input_field=$field\">$_folder</a></li>";
          }          
          $directories.="</ul>\n";         
          $folders[$ident]=null;
       }
       $directories.="</li>";      
     }
   }
 }
 $directories="<li><a href=\"".$CFG->wwwroot."mod/file/file_include_wizard.php?owner=$owner&input_field=$field\" >".__gettext("Root")."</a><ul>\n".$directories."</ul>\n</li>";
 
 // Files 
 $folder_name = (is_object($folder_object))?$folder_object->name:__gettext("Root");
 // I don't know why when I pass the owner param the query returns a bad object
 // $user_files = get_records('files','folder',$folder_id,"files_owner",$owner);
 $user_files = get_records_sql("select * from {$CFG->prefix}files where folder = {$folder_id} and files_owner = {$owner}");

 if(!empty($user_files)){
   $files="<ul>";
   foreach($user_files as $file){
     $file_name = (!empty($file->title))?$file->title:$file->originalname;
     $extension = strtolower(substr($file->originalname,strpos($file->originalname,".")+1));
     $type=(array_key_exists($extension,get_mimetype_array()))?" $extension":"";
     if(ALLOW_WIZARD_FILE_DELETE){
        //FIXME: set form key to pass require_confirm
        $form_key = elggform_key_get('confirm');

       $redirect_url = "{$CFG->wwwroot}mod/file/file_include_wizard.php?owner={$owner}&folder={$folder_id}";
       $delete_msg = __gettext("Are you sure you want to permanently delete this file?");
       $delete="&nbsp;&nbsp;";
       $delete.="<a onclick=\"return confirm('$delete_msg')\" href=\"{$CFG->wwwroot}mod/file/action_redirection.php?action=delete_file&delete_file_id={$file->ident}&redirection=".rawurlencode($redirect_url)."&amp;form_key=$form_key\">";
       $delete.="<img src=\"{$CFG->wwwroot}mod/file/fileicons/del.png\" border=\"0\"></a>";
     }
     $files.="<li><a class=\"mediafile$type\" href=\"#\" onclick=\"{$function_name}('$field','$file->ident')\">$file_name</a>$delete</li>";
   }
   $files.="</ul>";
 }
 else{
   $files = "<p>".__gettext("Empty directory!")."</p>";
 }

 if(ALLOW_WIZARD_UPLOAD){
   $folder= $folder_id;
   $files.= run("files:wizard:add:file"); 
 }
 $run_result = templates_draw(array('context'=>'file_wizard',
                                    'title'=> $CFG->sitename." :: ".__gettext("File selection"),
                                    'directories' => $directories,
                                    'folder_name' => $folder_name,
                                    'directory_files' => $files,
                                    'window_msg' => $keepopen
                                    )
                             );

 echo $run_result;

?>
