<?php

// View a weblog's posts for a particular month

// Get the current profile ID

global $profile_id;
global $db;
global $CFG;

// If the months haven't been set, they're the current months
$month = optional_param('month',gmdate('m'),PARAM_INT);

// If the years haven't been set, they're the current years
$year = optional_param('year',gmdate('y'),PARAM_INT);

// Get all posts in the system that we can see

$where = run("users:access_level_sql_where",$_SESSION['userid']);

//Getting the field from the context extension
$extensionContext = trim(optional_param('extension','weblog'));

$where2 = "";
if(is_array($CFG->weblog_extensions)){
  if($extensionContext!='weblog' && array_key_exists($extensionContext,$CFG->weblog_extensions)){
    if(array_key_exists('type',$CFG->weblog_extensions[$extensionContext])){
      $extraType  = $CFG->weblog_extensions[$extensionContext]['type'];
      $where2 = "AND ident IN(select ref FROM ".$CFG->prefix."tags WHERE tagtype='weblog' AND tag=".$db->qstr($extraType).")";
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
      $where2 = "AND ident not IN(select ref FROM ".$CFG->prefix."tags WHERE tagtype='weblog' AND tag in (".$nofilter."))";
    }
  }
}

$posts = get_records_select('weblog_posts','('.$where.') AND weblog = '.$profile_id ." $where2
                             AND posted >= ".mktime(0,0,0,$month,1,$year)."
                             AND posted < ".mktime(0,0,0,($month + 1), 1, $year),
                            null,'posted ASC');

if (!empty($posts)) {

    $lasttime = "";

    $run_result .= "<h1 class=\"weblogdateheader\">" . ucfirst(strftime("%B %Y", mktime(0,0,0,$month,1,$year))) . "</h1>\n";

    foreach($posts as $post) {

        $time = ucfirst(strftime("%B %d, %Y", $post->posted));
        if ($time != $lasttime) {
            $run_result .= "<h2 class=\"weblogdateheader\">$time</h2>\n";
            $lasttime = $time;
        }

        $run_result .= run("weblogs:posts:view",$post);

    }

}

?>