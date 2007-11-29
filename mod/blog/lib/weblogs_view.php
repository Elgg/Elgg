<?php

// View a weblog

// Get the current profile ID

global $profile_id, $CFG, $db;

// If the weblog offset hasn't been set, it's 0
$weblog_offset = optional_param('weblog_offset',0,PARAM_INT);
$filter = optional_param('filter');
$nofilter= "";


//Getting the field from the context extension
$extensionContext = trim(optional_param('extension','weblog'));

if(is_array($CFG->weblog_extensions)){
  if($extensionContext!='weblog' && array_key_exists($extensionContext,$CFG->weblog_extensions)){
    $extraType  = (array_key_exists('type',$CFG->weblog_extensions[$extensionContext]))?$CFG->weblog_extensions[$extensionContext]['type']:"";
    $filter = $extraType;
  }
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
  $nofilter = implode(',',array_map(array($db,'qstr'),$nofilter));
}

// Get all posts in the system that we can see

$where = run("users:access_level_sql_where",$_SESSION['userid']);
if (empty($filter)  && empty($nofilter)) {
    $posts = get_records_select('weblog_posts','('.$where.') AND weblog = '.$profile_id,null,'posted DESC','*',$weblog_offset,POSTS_PER_PAGE);
    $numberofposts = count_records_select('weblog_posts','('.$where.') AND weblog = '.$profile_id);
}
else if(!empty($nofilter) && empty($filter)){
    $posts = get_records_sql("select * from ".$CFG->prefix."weblog_posts WHERE ($where) AND weblog = $profile_id AND ident not in (select distinct ref from ".$CFG->prefix."tags where tagtype='weblog' AND tag in ($nofilter)) order by posted desc limit $weblog_offset,".POSTS_PER_PAGE);
    $numberofposts = get_record_sql("select count(*) as numberofposts from ".$CFG->prefix."weblog_posts WHERE ($where) AND weblog = $profile_id AND ident not in (select distinct ref from ".$CFG->prefix."tags where tagtype='weblog' AND tag in ($nofilter))");
    $numberofposts = $numberofposts->numberofposts;
}
else {
    $where = str_replace("access","wp.access",$where);
    $where = str_replace("owner","wp.owner",$where);
    $filter = (is_array($filter)) ? array_map(array($db,'qstr'),$filter):$db->qstr($filter);
    $posts = get_records_sql("select * from ".$CFG->prefix."tags t join ".$CFG->prefix."weblog_posts wp on wp.ident = t.ref where ($where) AND t.tagtype = 'weblog' AND wp.weblog = $profile_id AND t.tag in( " . $filter . ") order by posted desc limit $weblog_offset,".POSTS_PER_PAGE);
    $numberofposts = get_record_sql("select count(wp.ident) as numberofposts from ".$CFG->prefix."tags t join ".$CFG->prefix."weblog_posts wp on wp.ident = t.ref where ($where) AND t.tagtype = 'weblog' AND wp.weblog = $profile_id AND t.tag in (" . $filter.")");
    $numberofposts = $numberofposts->numberofposts;
}

if (!empty($posts)) {

    $lasttime = "";

    foreach($posts as $post) {

        $time = ucfirst(strftime("%B %d, %Y",$post->posted));
        if ($time != $lasttime) {
            $run_result .= "<h2 class=\"weblog_dateheader\">$time</h2>\n";
            $lasttime = $time;
        }

        $run_result .= run("weblogs:posts:view",$post);

    }

    if (!empty($filter)) {
        $filterlink = "category/".urlencode($filter)."/";
    } else {
        $filterlink = "";
    }

    $weblog_name = htmlspecialchars(optional_param('weblog_name'), ENT_COMPAT, 'utf-8');

    if ($numberofposts - ($weblog_offset + POSTS_PER_PAGE) > 0) {
        $display_weblog_offset = $weblog_offset + POSTS_PER_PAGE;
        $back = __gettext("Back");
        $run_result .= <<< END

                <a href="{$CFG->wwwroot}{$weblog_name}/{$extensionContext}/{$filterlink}skip={$display_weblog_offset}">&lt;&lt; $back</a>

END;
    }
    if ($weblog_offset > 0) {
        $display_weblog_offset = $weblog_offset - POSTS_PER_PAGE;
        if ($display_weblog_offset < 0) {
            $display_weblog_offset = 0;
        }
        $next = __gettext("Next");
        $run_result .= <<< END

                <a href="{$CFG->wwwroot}{$weblog_name}/{$extensionContext}/{$filterlink}skip={$display_weblog_offset}">$next &gt;&gt;</a>

END;
    }

}
else{
  $type =(isset($extraType))?$extraType:$extensionContext;
  $run_result = "<p>".sprintf(__gettext("You currently don't have any %s"), strtolower($type))."</p>";
}
?>