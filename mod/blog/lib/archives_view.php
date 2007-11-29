<?php

global $CFG;
global $db;
// Get the current profile ID

global $profile_id;

// Obtain the separate archive pages from the database
if ($CFG->dbtype == 'mysql') {
    $field = 'EXTRACT(YEAR_MONTH FROM FROM_UNIXTIME(posted))';
} elseif ($CFG->dbtype == 'postgres7') {
    $field = 'to_char(TIMESTAMP WITH TIME ZONE \'epoch\' + posted * interval \'1 second\',\'YYYYMM\')';
}
$extensionContext = trim(optional_param('extension','weblog'));
$where3="";
if(is_array($CFG->weblog_extensions)){
  if($extensionContext!='weblog' && array_key_exists($extensionContext,$CFG->weblog_extensions)){
    if(array_key_exists('type',$CFG->weblog_extensions[$extensionContext])){
      $extraType  = $CFG->weblog_extensions[$extensionContext]['type'];
      $where3 = "AND ident IN(select ref FROM ".$CFG->prefix."tags WHERE tagtype='weblog' AND tag=".$db->qstr($extraType).")";
    }
  }
  else{
    //Not show the extra context contents
    $nofilter = array();
    foreach($CFG->weblog_extensions as $key => $value){
      if($key!='weblog' && array_key_exists('type',$value)){
        $nofilter[] = $value['type'];
      }
      if($key!='weblog' && array_key_exists('values',$value)){
        if(is_array($value['values'])){
          $nofilter=array_merge($value['values'],$nofilter);
        }
        else{
          $nofilter[] = $value['type'];
        }
      }
    }
    if(!empty($nofilter)){
      $nofilter = implode(',',array_map(array($db,'qstr'),$nofilter));
      $where3 = "AND ident not IN(select ref FROM ".$CFG->prefix."tags WHERE tagtype='weblog' AND tag in (".$nofilter."))";
    }
  }
}

if ($archives = get_records_sql('SELECT DISTINCT '.$field.' as archivestamp, posted
                                    FROM '.$CFG->prefix.'weblog_posts wp
                                    WHERE wp.weblog = ? '.$where3.' ORDER BY posted DESC',array($profile_id))) {


// If there are any archives ...
$archive = __gettext("Weblog Archive"); // gettext variable
$run_result .= "<h1 class=\"weblogdateheader\">$archive</h1>";

    // Get the name of the weblog user

    $weblog_name = htmlspecialchars(optional_param('weblog_name'), ENT_COMPAT, 'utf-8');

    // Run through them

    $lastyear = 0;

    foreach($archives as $archive) {

        // Extract the year and the month

        $year = strftime("%Y", $archive->posted);;
        $month = strftime("%m", $archive->posted);;

        if ($year != $lastyear) {
            if ($lastyear != 0) {
                $run_result .= "</ul>";
            }
            $lastyear = $year;
            $run_result .= "<h2 class=\"weblogdateheader\">$year</h2>";
            $run_result .= "<ul>";
        }

        // Print a link

        $run_result .= "<li>";
        $run_result .= "<a href=\"" . url . $weblog_name . "/{$extensionContext}/archive/$year/$month/\">";
        $run_result .= ucfirst(strftime("%B %Y", $archive->posted));
        $run_result .= "</a>";
        $run_result .= "</li>";
    }

    $run_result .= "</ul>";

    // If there are no posts to archive, say so!

} else {
  $type =(isset($extraType))?$extraType:$extensionContext;
  $run_result = "<p>".sprintf(__gettext("You currently don't have any %s to archive"), strtolower($type))."</p>";
}

?>