<?php

    /*
     * Explode pinger
     * This periodically lets the Explode service know that this site exists,
     * but will refuse to run if walled garden restrictions are enabled.
     * You can delete this module with no ill effects, simply by removing
     * the whole /mod/explodeping/ directory.
     */
     
     function explodeping_pagesetup() {
     }
     
     /*
      * Checks to see if we've pinged Explode yet; if not, sends information
      * about the URL and so on, and gets a service number and key back.
      * If it has, sends another ping every two weeks with the most active
      * user and the key, in order to instigate crawling.
      * @uses $CFG;
      */
     function explodeping_init() {
         global $CFG, $messages;

         // FIXME: workaround to annoying warning when is enabled open_basedir 
         // restriction
         if (@ini_get('open_basedir') || !ini_get_bool('allow_url_fopen')) { return;}

         if (!$explodeservice = get_record('datalists', 'name', 'explodeservice')) {
             
             ini_set('default_socket_timeout', 20);
             
             $pingvars = "pingtype=registernew";
             $pingvars .= "&url=" . urlencode($CFG->wwwroot);
             $pingvars .= "&profileurl=" . urlencode($CFG->wwwroot . "%username%");
             $pingvars .= "&name=" . urlencode($CFG->sitename);
             $pingvars .= "&rssurl=" . urlencode($CFG->wwwroot . "%username%/rss");
             $pingvars .= "&foafurl=" . urlencode($CFG->wwwroot . "%username%/foaf");
             $pingresponse = file_get_contents("http://ex.plode.us/mod/searchping/elggping.php?{$pingvars}");
             
             if (user_flag_get("admin",$_SESSION['userid'])) {
                 $messages[] = str_replace("&","<br />",$pingvars);
                 $messages[] = $pingresponse;
             }
             
             if (!empty($pingresponse)) {
                 if ($uspingresponse = unserialize($pingresponse)) {
                     $datalist = new stdClass;
                     $datalist->name = 'explodeservice';
                     $datalist->value = $pingresponse;
                     insert_record('datalists',$datalist);
                 }
             }
             
         } else {
             
             $explodelastpinged = get_record('datalists', 'name', 'explodelastpinged');
             $triggertime = time() - (86400* 7);
             if (!$explodelastpinged || $explodelastpinged->value < $triggertime) {
                 
                 //reduce likelihood of concurrent pings on a stall
                 delete_records('datalists','name','explodelastpinged');
                 $datalist = new stdClass;
                 $datalist->name = 'explodelastpinged';
                 $datalist->value = $triggertime + 600;
                 insert_record('datalists',$datalist);
                 ini_set('default_socket_timeout', 20);
                 
                 //don't do anything if initial connect doesn't work
                 $testresponse = file_get_contents("http://ex.plode.us/mod/searchping/elggping.php");
                 if ($testresponse !== false) {
                     
                     $search_sql = "SELECT u.ident, u.username, COUNT(m.ident) AS members FROM `".$CFG->prefix."users` u JOIN ".$CFG->prefix."friends m ON m.owner = u.ident WHERE u.user_type = 'person' GROUP BY u.ident ORDER BY members DESC LIMIT 1";
                     if ($users = get_records_sql($search_sql)) {
                         
                         foreach($users as $user) {
                             $username = $user->username;
                             
                             $explodeservice = get_record_sql("select * from {$CFG->prefix}datalists where name = 'explodeservice'"); // ('datalists', 'name', 'explodeservice');
                             $explodeservice = unserialize($explodeservice->value);
                             $crypt_reping = sha1($explodeservice->ident . ":" . $username . ":" . $explodeservice->secretkey);
                             
                             $pingvars = "pingtype=reping";
                             $pingvars .= "&service=" . urlencode($explodeservice->ident);
                             $pingvars .= "&crypt=" . urlencode($crypt_reping);
                             $pingvars .= "&username=" . urlencode($username);
                             
                             $response = file_get_contents("http://ex.plode.us/mod/searchping/elggping.php?{$pingvars}");
                        }
                        
                     }
                     
                     delete_records('datalists','name','explodelastpinged');
                     $datalist = new stdClass;
                     $datalist->name = 'explodelastpinged';
                     $datalist->value = time();
                     insert_record('datalists',$datalist);
                     
                 }
             }
             
         }
     }

?>