<?php

// View a weblog

// Get the current profile ID

global $page_owner;
global $CFG;
global $db;

// If the weblog offset hasn't been set, it's 0
$weblog_offset = optional_param('weblog_offset',0,PARAM_INT);

// Get all posts in the system by our friends that we can see

$friends = run("friends:get",array($page_owner));


$where2 = "weblog IN (" ;
$first=true;
// Uncoment the following lines if you want to see your own post in the friends list
//$where2 .= $page_owner;
//$first=false;

if (!empty($friends)) {
  foreach($friends as $friend) {
    if (!$first) {
      $where2 .= ", ";
    }
    $where2 .= $friend->user_id;
    $first=false;
  }
}
$where2 .= ")";

//Getting the field from the context extension
$extensionContext = trim(optional_param('extension','weblog'));

$where3 = "";
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

$where1 = run("users:access_level_sql_where",$_SESSION['userid']);
// if (!isset($_SESSION['friends_posts_cache']) || (time() - $_SESSION['friends_posts_cache']->created > 60)) {
// $_SESSION['friends_posts_cache']->created = time();
// $_SESSION['friends_posts_cache']->data = get_records_select('weblog_posts','('.$where1.') AND ('.$where2.')',null,'posted DESC','*',$weblog_offset,25);
// }
// $posts = $_SESSION['friends_posts_cache']->data;
error_log('('.$where1.') AND ('.$where2.') '.$where3);
$posts = get_records_select('weblog_posts','('.$where1.') AND ('.$where2.') '.$where3,null,'posted DESC','*',$weblog_offset,POSTS_PER_PAGE);
$numberofposts = count_records_select('weblog_posts','('.$where1.') AND ('.$where2.') ' .$where3);
if (!empty($posts)) {

    $lasttime = "";

    foreach($posts as $post) {

        $time = gmstrftime("%B %d, %Y",$post->posted);
        if ($time != $lasttime) {
            $run_result .= "<h2 class=\"weblogdateheader\">$time</h2>\n";
            $lasttime = $time;
        }

        $run_result .= run("weblogs:posts:view",$post);

    }

    $weblog_name = htmlspecialchars(optional_param('weblog_name'), ENT_COMPAT, 'utf-8');

    if ($numberofposts - ($weblog_offset + POSTS_PER_PAGE) > 0) {
        $display_weblog_offset = $weblog_offset + POSTS_PER_PAGE;
        $back = __gettext("Back"); // gettext variable
        $run_result .= <<< END

                <a href="{$CFG->wwwroot}{$weblog_name}/{$extensionContext}/friends/skip={$display_weblog_offset}">&lt;&lt; $back</a>
                <!-- <form action="" method="post" style="display:inline">
                    <input type="submit" value="&lt;&lt; Previous 25" />
                    <input type="hidden" name="weblog_offset" value="{$display_weblog_offset}" />
                </form> -->

END;
    }
    if ($weblog_offset > 0) {
        $display_weblog_offset = $weblog_offset - POSTS_PER_PAGE;
        if ($display_weblog_offset < 0) {
            $display_weblog_offset = 0;
        }
        $next = __gettext("Next"); // gettext variable
        $run_result .= <<< END

                <a href="{$CFG->wwwroot}{$weblog_name}/{$extensionContext}/friends/skip={$display_weblog_offset}">$next &gt;&gt;</a>

END;
    }

}
else{
  $type =(isset($extraType))?$extraType:$extensionContext;
  $run_result = "<p>".sprintf(__gettext("Your friends currently don't have any %s"), strtolower($type))."</p>";  
}
?>