<?php

// accept a file send from the lms
require_once(dirname(dirname(__FILE__)).'/includes.php');
require_once($CFG->dirroot.'lib/lmslib.php');
require_once($CFG->dirroot.'lib/uploadlib.php');

$textlib = textlib_get_instance();

// the POST parameters we expect are:
$installid = optional_param('installid');
$username = optional_param('username');
$filename = optional_param('filename');
$action = optional_param('action');
$size = optional_param('size',0,PARAM_INT);
$signature = optional_param('signature');
$foldername = optional_param('foldername');

// verify the signature and find our user...
$user = find_lms_user($installid,$username,$signature);
if (!is_object($user)) {
    echo $user;
    die();
} 

switch ($action) {
 case 'intention':
     $incoming = new StdClass;
     $incoming->installid = $installid;
     $incoming->intentiondate = time();
     $incoming->user_id = $user->ident;
     $incoming->foldername = $foldername; // should relate to course name.
     $incoming->size = $size;
     $id = insert_record('files_incoming',$incoming);

     // create the holding directory if it doesn't already exist...
     $dir = 'lms/incoming/'.$user->ident;

     if (!make_upload_directory($dir,false)) {
         echo 'Could not create holding directory';
         die();
     }
     $filename = $CFG->dataroot.$dir.'/'.$id.'-'.time().'.zip';

     echo 'OK|'.$filename;
     break;

 case 'done':
     // first try and find the 'incoming' record that matches.
     $m = array();
     if (!preg_match('/(\d+)\-(\d+).zip/',$filename,$m)) {
         echo 'couldn\'t find information about the specified file';
         break;
     }
     if (!$intention = get_record('files_incoming','ident',$m[1])) {
         echo 'couldn\'t find information about the specified file';
         break;
     }
     
     // check the date  (should be within an hour)
     if ($intention->intentiondate < (time()-(60*60))) {
         echo 'intention too old';
         break;
     } 
     // find the file
     $filepath = $CFG->dataroot.'lms/incoming/'.$intention->user_id.'/'.$filename;
     if (!file_exists($filepath)) {
         echo 'couldn\'t find file';
         break;
     }
     // check filesize
     $actualsize = filesize($filepath);
     if ($actualsize != $intention->size) {
         echo 'filesize of received file ('.$actualsize.') didn\'t match expected size ('.$intention->size.')';
         break;
     }

     // make a temporary directory to unzip the file into
     $tempdir = 'temp/lms/'.$intention->user_id.'/'.time();
     if (!make_upload_directory($tempdir,false)) {
         echo 'Could not create temporary directory structure to extract the files into';
         break;
     }
     $tempdir = $CFG->dataroot . $tempdir;

     if (!unzip_file($filepath,$tempdir,false)) {
         echo 'Could not unzip the file to the temporary directory';
         break;
     }

     // read & parse the manifest file
     $manifest = $tempdir.'/manifest.txt';
     if (!file_exists($manifest)) {
         echo 'Could not find the manifest file';
         break;
     }

     if (!$contents = file($manifest)) {
         echo 'Could not read the contents of the manifest file';
         break;
     }
     
     if (count($contents) == 0) {
         echo 'Manifest file was empty!';
         break;
     }

     $files = array(); // (temp) we need to rejuggle filenames to handle collisions.
     $fileinfo = array(); // this is our proper data structure.
     $ul_username = $user->username;
     $upload_folder = $textlib->substr($ul_username,0,1);
     $destination = "files/" . $upload_folder . "/" . $ul_username ;
     $relativedestination = $destination;
     make_upload_directory($destination,false);
     $destination = $CFG->dataroot . $destination;

     // first do some basic validation.
     foreach ($contents as $line) {
         $bits = explode('|',$line,2); // limit set to 2 so we just get filename|decription
         if (count($bits) != 2) { // houston, we have a problem! 
             echo "Something wrong in manifest file, with line $line";
             break 2;
         }
         if (!file_exists($tempdir.'/'.$bits[0])) {
             echo "Manifest file points to ".$bits[0]." but it doesn't exist!";
             break 2;
         }
         $files[] = $bits[0];
     }
     
     // rejuggle to handle filename conflicts...
     $files = resolve_filename_collisions($destination,$files);
     
     // keys should still match, make up the proper data structure...
     foreach ($files as $k => $f) {
         $file = new StdClass;
         $file->filename = $f;
         $line = $contents[$k];
         $bits = explode('|',$line,2); // limit set to 2 so we just get filename|decription
         $file->description = $bits[1];
         $file->originalname = $bits[0];
         $fileinfo[] = $file;
     }

     begin_sql(); // do it transactionally if we CAN
         
     // start looking for the database stuff we need....
     $folder = lms_get_folder($installid,$intention->foldername,$user);

     foreach ($fileinfo as $tosave) {
         // first move the file to where it should go.
         if (!copy($tempdir.'/'.$tosave->originalname,$destination.'/'.$tosave->filename)) {
             echo 'Failed to save some files to the final destination!';
             rollback_sql();
             break 2;
         }
         $f = new StdClass;
         $f->owner = $user->ident;
         $f->files_owner = $user->ident;
         $f->folder = $folder->ident;
         $f->originalname = $tosave->originalname;
         $f->title = $tosave->originalname;
         $f->description = $tosave->description;
         $f->location = $relativedestination.'/'.$tosave->filename;
         $f->access = 'user'.$user->ident; // ew again.
         $f->size = filesize($destination.'/'.$tosave->filename);
         $f->time_uploaded = time();
         if (!insert_record('files',$f)) {
             echo 'Failed to save some file information to the database! Some files may have copied across ok';
             rollback_sql();
             break 2;
         }
     }

     commit_sql();

     // remove all the temporary stuff and delete the record from the queue.
     delete_records('files_incoming','ident',$intention->ident);
     delete_records_select('files_incoming','intentiondate < ?',array(time()-60*60));
     
     @unlink($filepath); // we may not have permissions to do this ...
     remove_dir($tempdir);

     echo 'OK';
     break;
}
?>