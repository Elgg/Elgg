<?php

// very basic cron functionality.
// later this will go through /mod 
// and things like auth plugins
// but we don't have those yet, so...

$starttime = microtime();

require_once(dirname(__FILE__)."/includes.php");

$timenow  = time();

mtrace("<pre>");
mtrace("Server Time: ".date('r',$timenow)."\n\n");

$lastcron = $CFG->lastcron;
if (empty($lastcron)) {
    $lastcron = 0;
}

// we only want to do this once a day because it could take awhile...
if ($timenow - (60*60*24) > $lastcron) {
    mtrace('Cleaning up old incoming files... ','');
    // clean up the lms incoming files 
    delete_records_select('files_incoming','intentiondate < ?',array(time()-60*60*24));

    $dirtocheck = $CFG->dataroot.'temp/lms/';    
    $cmd = "find $dirtocheck -type d -cmin 1440 -print0 | xargs -0 -r rm -rf";
    exec($cmd);
    
    $dirtocheck = $CFG->dataroot.'lms/incoming';
    // this is not going to work unless this script is running as either the owner of these files
    // or root.
    $cmd = "find $dirtocheck -type d -cmin 1440 -print0 | xargs -0 -r rm -rf";
    exec($cmd);

    mtrace('done');
}

// module cron
if ($mods = get_list_of_plugins()) {
    foreach ($mods as $mod) {
        $libfile = $CFG->dirroot.'mod/'.$mod.'/lib.php';
        if (!file_exists($libfile)) {
            continue;
        }
        require_once($libfile);
        $cronfunction = $mod.'_cron';
        if (!function_exists($cronfunction)) {
            continue;
        }
        // each module is responsible for checking their last runtime and not running again if it's too soon.
        mtrace('Running cron for '.$mod.'...','');
        $cronfunction();
        mtrace('Done');
    }
}


if (!set_config('lastcron',$timenow)) {
    mtrace('Could not update last cron time to now!');
}

mtrace("Cron script completed correctly");

$difftime = microtime_diff($starttime, microtime());
mtrace("Execution took ".$difftime." seconds"); 

?>