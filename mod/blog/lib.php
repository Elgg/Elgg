<?php
function blog_pagesetup() {
  // register links --
  global $profile_id;
  global $PAGE;
  global $CFG;
  require_once (dirname(__FILE__))."/default_template.php";

  $page_owner= $profile_id;

  // main menu
  if (isloggedin()) {

    if (defined("context") && context == "weblog" && $page_owner == $_SESSION['userid']) {

      $PAGE->menu[]= array (
        'name' => 'weblog',
        'html' => "<li><a href=\"{$CFG->wwwroot}{$_SESSION['username']}/weblog\" class=\"selected\" >" . __gettext("Your Blog") . '</a></li>');

    } else {
      $PAGE->menu[]= array (
        'name' => 'weblog',
        'html' => "<li><a href=\"{$CFG->wwwroot}{$_SESSION['username']}/weblog\" >" . __gettext("Your Blog") . '</a></li>');
    };
  }

  $weblog_username= user_info('username', $page_owner);

  // submenu
  if (defined("context") && context == "weblog") {

    if ($page_owner != -1) {

      $PAGE->menu_sub[]= array (
        'name' => 'blog:rssfeed',
        'html' => "<a href=\"{$CFG->wwwroot}{$weblog_username}/weblog/rss/\"><img src=\"{$CFG->wwwroot}mod/template/icons/rss.png\" border=\"0\" alt=\"rss\" /></a>"
      );

      if (run("permissions:check", "weblog") && logged_on) {
        $PAGE->menu_sub[]= array (
          'name' => 'blog:post',
          'html' => "<a href=\"{$CFG->wwwroot}{$weblog_username}/weblog/edit\">" . __gettext("Post a new entry") . '</a>');
      }

      $PAGE->menu_sub[]= array (
        'name' => 'blog:view',
        'html' => "<a href=\"{$CFG->wwwroot}{$weblog_username}/weblog/\">" . __gettext("View blog") . '</a>');

      $PAGE->menu_sub[]= array (
        'name' => 'blog:archive',
        'html' => "<a href=\"{$CFG->wwwroot}{$weblog_username}/weblog/archive/\">" . __gettext("Archive") . '</a>');

      $PAGE->menu_sub[]= array (
        'name' => 'blog:friends',
        'html' => "<a href=\"{$CFG->wwwroot}{$weblog_username}/weblog/friends/\">" . __gettext("Friends' blogs") . '</a>');

      if ($page_owner == $_SESSION['userid']) {
        $PAGE->menu_sub[]= array (
          'name' => 'blog:interesting',
          'html' => "<a href=\"{$CFG->wwwroot}{$weblog_username}/weblog/interesting/\">" . __gettext("Interesting posts") . '</a>');
      }
    }

    $PAGE->menu_sub[]= array (
      'name' => 'blog:everyone',
      'html' => "<a href=\"{$CFG->wwwroot}weblog/everyone\">" . __gettext("View all posts") . '</a>');

  }

}

function blog_init() {

  global $CFG, $function;


    // Functions to perform upon initialisation
        $function['weblogs:init'][] = $CFG->dirroot . "mod/blog/lib/weblogs_init.php";
        $function['weblogs:init'][] = $CFG->dirroot . "mod/blog/lib/weblogs_actions.php";

    // Load default template
        include($CFG->dirroot . "mod/blog/default_template.php");

    // Init for search
        $function['search:init'][] = $CFG->dirroot . "mod/blog/lib/weblogs_init.php";
        $function['search:all:tagtypes'][] = $CFG->dirroot . "mod/blog/lib/function_search_all_tagtypes.php";

    // Function to search through weblog posts
        $function['search:display_results'][] = $CFG->dirroot . "mod/blog/lib/function_search.php";
        $function['search:display_results:rss'][] = $CFG->dirroot . "mod/blog/lib/function_search_rss.php";

    // Edit / create weblog posts
        $function['weblogs:edit'][] = $CFG->dirroot . "mod/blog/lib/weblogs_edit.php";
        $function['weblogs:posts:add'][] = $CFG->dirroot . "mod/blog/lib/weblogs_posts_add.php";
        $function['weblogs:posts:edit'][] = $CFG->dirroot . "mod/blog/lib/weblogs_posts_edit.php";

    // View weblog posts
        //$function['weblogs:view'][] = $CFG->dirroot . "mod/blog/lib/weblogs_post_field_wrapper.php";
        $function['weblogs:view'][] = $CFG->dirroot . "mod/blog/lib/weblogs_view.php";
        $function['weblogs:posts:view'][] = $CFG->dirroot . "mod/blog/lib/weblogs_posts_view.php";
        $function['weblogs:posts:view:individual'][] = $CFG->dirroot . "mod/blog/lib/weblogs_posts_view.php";
        $function['weblogs:friends:view'][] = $CFG->dirroot . "mod/blog/lib/weblogs_friends_view.php";
        $function['weblogs:everyone:view'][] = $CFG->dirroot . "mod/blog/lib/weblogs_all_users_view.php";
        
        // This is necessary to ensure that the blog text process function its the first one to be called
        if(array_key_exists("weblogs:text:process",$function)){
          $function['weblogs:text:process'] = array_merge(array($CFG->dirroot ."mod/blog/lib/weblogs_text_process.php"),$function['weblogs:text:process']);
        }
        else{
          $function['weblogs:text:process'][] = $CFG->dirroot . "mod/blog/lib/weblogs_text_process.php";
        }
        $function['weblogs:archives:view'][] = $CFG->dirroot . "mod/blog/lib/archives_view.php";
        $function['weblogs:archives:month:view'][] = $CFG->dirroot . "mod/blog/lib/weblogs_view_month.php";
        $function['weblogs:interesting:view'][] = $CFG->dirroot . "mod/blog/lib/weblogs_interesting_view.php";

    // Mark posts as interesting (or not)
        $function['weblogs:interesting:form'][] = $CFG->dirroot . "mod/blog/lib/display_interesting_post_form.php";

    // Edit / create weblog comments
        $function['weblogs:comments:add'][] = $CFG->dirroot . "mod/blog/lib/weblogs_comments_add.php";

    // Log on bar down the right hand side
        // $function['profile:log_on_pane'][] = $CFG->dirroot . "units/weblogs/weblogs_user_info_menu.php";
        $function['display:sidebar'][] = $CFG->dirroot . "mod/blog/lib/weblogs_user_info_menu.php";

    // Weblog preview
        $function['templates:preview'][] = $CFG->dirroot . "mod/blog/templates_preview.php";

    // Establish permissions
        $function['permissions:check'][] = $CFG->dirroot . "mod/blog/lib/permissions_check.php";

    // Actions to perform when an access group is deleted
        $function['groups:delete'][] = $CFG->dirroot . "mod/blog/lib/groups_delete.php";

    // Publish static RSS file of posts
        $function['weblogs:rss:getitems'][] = $CFG->dirroot . "mod/blog/lib/function_rss_getitems.php";
        $function['weblogs:rss:publish'][] = $CFG->dirroot . "mod/blog/lib/function_rss_publish.php";

    // Removing function from weblogs_init.php
        $function['weblogs:html_activate_urls'][] = $CFG->dirroot . "mod/blog/lib/function_html_activate_urls.php";

  //$CFG->widgets->display['blog'] = "blog_widget_display";
  //$CFG->widgets->edit['blog'] = "blog_widget_edit";

  $CFG->widgets->list[]= array ('name' => __gettext("Blog widget"),
                                'description' => __gettext("Displays the latest blog posts from a blog of your choice."),
                                'type' => "blog::blog");

  $CFG->templates->variables_substitute['blog'][]= "blog_keyword";
  $CFG->templates->variables_substitute['blogsummary'][]= "blog_summary_keyword";
  $CFG->templates->variables_substitute['blogexecutivesummary'][] = "blog_executive_summary_keyword";
  // Delete users
  listen_for_event("user", "delete", "blog_user_delete");

  if (!isset ($CFG->display_field_module)) {
    $CFG->display_field_module= array ();
  }

  if (!array_key_exists("select", $CFG->display_field_module)) {
    $CFG->display_field_module["select"]= "blog";
  }
  if (!array_key_exists("selectg", $CFG->display_field_module)) {
    $CFG->display_field_module["selectg"]= "blog";
  }
  if (!array_key_exists("selectd", $CFG->display_field_module) && !array_key_exists("date_select", $CFG->display_field_module)) {
    $CFG->display_field_module["selectd"]= "blog";
    $CFG->display_field_module["date_select"]= "blog";
  }
  if (!array_key_exists("radio", $CFG->display_field_module)) {
    $CFG->display_field_module["radio"]= "blog";
  }
  if (!array_key_exists("vertical_radio", $CFG->display_field_module)) {
    $CFG->display_field_module["vertical_radio"]= "blog";
  }
  
  if (!isset ($CFG->weblog_extensions)) {
    $CFG->weblog_extensions= array ();
  }  
  $CFG->weblog_extensions['weblog']= array ();



	// Register file river hook (if there)
	if (function_exists('river_save_event'))
	{
		listen_for_event('weblog_post','publish', 'blog_river_hook');
		listen_for_event('weblog_post','delete', 'blog_river_hook');

		river_register_friendlyname_hook('weblog_post::post', 'blog_get_friendly_name');
	}
  
}

function blog_get_friendly_name($object_type, $object_id)
{
	global $CFG;

	if ($object_type == 'weblog_post::post')
	{
		$record = get_record_sql("SELECT * from {$CFG->prefix}weblog_posts where ident=$object_id");

		if ($record)
		{
			$blog = user_info("username", $record->weblog);
			$url = river_get_userurl($record->weblog);
			
			return sprintf(__gettext("a post in <a href=\"$url\">%s</a>'s weblog"), $blog);
		}
	}

	return "";
}

function blog_river_hook( $object_type, $event, $object)
{
	global $CFG;

	$userid = ($_SESSION['userid'] == "" ? -1 : $_SESSION['userid']);
	$object_id = $object->ident;
	$object_owner = $object->owner;
	$title = trim($object->title);
	$weblogid = $object->weblog;

	$username = user_info("username", $userid);
	$weblogname = "<a href=\"" . river_get_userurl($weblogid) . "\">". user_info("username", $weblogid) . "</a>'s";
	if ($userid == $weblogid) $weblogname = __gettext("their");

	$entryurl = $CFG->wwwroot . $username . "/weblog/" . $object->ident . ".html";

	$titletxt = "";	
	if ($title!="")
		$titletxt = sprintf( __gettext(" called '%s'"), $title);

	if ($username == false) $username = __gettext("Anonymous user");
	
	if ($event == "publish")
		river_save_event($userid, $object_id, $object_owner, $object_type, "<a href=\"" .  river_get_userurl($userid) . "\">$username</a> left a <a href=\"$entryurl\">post$titletxt</a> in $weblogname blog.");

	return $object;
}

function blog_widget_display($widget) {

  global $CFG;

  $blog_id= widget_get_data("blog_id", $widget->ident);
  $blog_posts= widget_get_data("blog_posts", $widget->ident);

  $body= "";

  if (empty ($blog_id)) {
    global $page_owner;
    $blog_id= $page_owner;
  }
  if (empty ($blog_posts)) {
    $blog_posts= 1;
  }

  $where= run("users:access_level_sql_where", $_SESSION['userid']);
  $posts= get_records_sql("select * from " . $CFG->prefix . "weblog_posts where ($where) and weblog = $blog_id order by posted desc limit $blog_posts");

  if (is_array($posts) && !empty ($posts)) {
    foreach ($posts as $post) {
      $body .= run("weblogs:posts:view", $post);
    }
  }

  return array (
    'title' => __gettext("Weblog"
  ), 'content' => $body);

}

// KJ - converted to widget sytem

function blog_widget_edit($widget) {

  global $CFG, $page_owner;

  $blog_id= widget_get_data("blog_id", $widget->ident);
  $blog_posts= widget_get_data("blog_posts", $widget->ident);
  if (empty ($blog_posts)) {
    $blog_posts= 1;
  }
  if (empty ($blog_id)) {
    $blog_id= $page_owner;
  }

  $connections= get_records_sql("select u.ident, u.name from " . $CFG->prefix . "friends f join " . $CFG->prefix . "users u on u.ident = f.friend where f.owner = " . $_SESSION['userid'] . " order by u.name asc");
  $data= new stdClass;
  $data->ident= $page_owner;
  $data->name= run("profile:display:name", $page_owner);
  $connections[]= $data;
  if ($page_owner != $_SESSION['userid']) {
    $data= new stdClass;
    $data->ident= $_SESSION['userid'];
    $data->name= run("profile:display:name", $_SESSION['userid']);
    $connections[]= $data;
  }

  $body= "<h2>" . __gettext("Blog widget") . "</h2>";
  $body .= "<p>" . __gettext("This widget displays the last couple of blog posts from an individual user. To begin, select the user from your connections below:") . "</p>";

  $body .= "<p><select name=\"widget_data[blog_id]\">\n";
  if (is_array($connections) && !empty ($connections)) {
    foreach ($connections as $connection) {
      if ($connection->ident == $blog_id) {
        $selected= "selected=\"selected\"";
      } else {
        $selected= "";
      }
      $body .= "<option value=\"" . $connection->ident . "\" $selected>" . $connection->name . "</option>\n";
    }
  }
  $body .= "</select></p>\n";

  $body .= "<p>" . __gettext("Then enter the number of blog posts you'd like to display:") . "</p>";

  $body .= "<p><input type=\"text\" name=\"widget_data[blog_posts]\" value=\"" . $blog_posts . "\" /></p>";

  return $body;

}

function blog_keyword($vars) {
  global $CFG, $db;

  $body= "";

  if (!isset ($vars[1])) {
    $blog_posts= 2;
  } else {
    $blog_posts= $vars[1];
  }

  $where= run("users:access_level_sql_where", $_SESSION['userid']);

  if (!isset ($vars[2]) || $vars[2] == "all") {
    $posts= get_records_sql("select * from " . $CFG->prefix . "weblog_posts where ($where) order by posted desc limit $blog_posts");
  } else {
    $blog_id= (int) user_info_username('ident', $vars[2]);
    $posts= get_records_sql("select * from " . $CFG->prefix . "weblog_posts where ($where) and weblog = $blog_id order by posted desc limit $blog_posts");
  }

  if (is_array($posts) && !empty ($posts)) {
    foreach ($posts as $post) {
      if ($vars[3] != "slim") {
        $body .= run("weblogs:posts:view", $post);
      } else {
        $body .= "<div class=\"frontpage-blog-contents\">";
        $body .= "<h4>" . $post->title . "</h4>";
        $body .= "<p class=\"frontpage-blog-date\">" . strftime("%B %d, %Y", $post->posted) . "</p>";
        $body .= "<p class=\"frontpage-blog-body\">" . run("weblogs:text:process", $post->body) . "</p>";
        $body .= "<p class=\"frontpage-blog-from\">" . __gettext("From:") . " <a href=\"{$CFG->wwwroot}" . user_info("username", $post->weblog) . "\">" . user_info("name", $post->weblog) . "</a> - ";
        $body .= "<a href=\"{$CFG->wwwroot}" . user_info("username", $post->weblog) . "/weblog/" . $post->ident . ".html\">" . __gettext("Read more") . "</a></p>";
        $body .= "</div>";
      }
    }
  }

  return $body;
}

function blog_summary_keyword($vars) {
  global $CFG;
  $body= "";

  if (!isset ($vars[1])) {
    $blog_posts= 2;
  } else {
    $blog_posts= $vars[1];
  }

  $where= run("users:access_level_sql_where", $_SESSION['userid']);

  if (!isset ($vars[2]) || $vars[2] == "all") {
    $posts= get_records_sql("select * from " . $CFG->prefix . "weblog_posts where ($where) order by posted desc limit $blog_posts");
  } else {
    $blog_id= (int) user_info_username('ident', $vars[2]);
    $posts= get_records_sql("select * from " . $CFG->prefix . "weblog_posts where ($where) and weblog = $blog_id order by posted desc limit $blog_posts");
  }

  if (is_array($posts) && !empty ($posts)) {
    foreach ($posts as $post) {
      $body .= "<div class=\"frontpage-blog-summary\">";
      $body .= "<h4>" . $post->title . "</h4>";
      $body .= "<p class=\"frontpage-blog-date\">" . strftime("%B %d, %Y", $post->posted) . "</p>";
      $body .= "<p class=\"frontpage-blog-from\">" . __gettext("From:") . " <a href=\"{$CFG->wwwroot}" . user_info("username", $post->weblog) . "\">" . user_info("name", $post->weblog) . "</a> - ";
      $body .= "<a href=\"{$CFG->wwwroot}" . user_info("username", $post->weblog) . "/weblog/" . $post->ident . ".html\">" . __gettext("Read more") . "</a></p>";
      $body .= "</div>";
    }
  }

  return $body;
}
        function blog_executive_summary_keyword($vars) {
            global $CFG;
            $body = "";
            
            if (!isset($vars[1])) {
                $blog_posts = 2;
            } else {
                $blog_posts = $vars[1];
            }

            $where = run("users:access_level_sql_where",$_SESSION['userid']);
                        
            if (!isset($vars[2]) || $vars[2] == "all") {
                $posts = get_records_sql("select * from ".$CFG->prefix."weblog_posts where ($where) order by posted desc limit $blog_posts");
            } else {
                $blog_id = (int) user_info_username('ident',$vars[2]);
                $posts = get_records_sql("select * from ".$CFG->prefix."weblog_posts where ($where) and weblog = $blog_id order by posted desc limit $blog_posts");
            }
            
            if (is_array($posts) && !empty($posts)) {
                foreach($posts as $post) {
                    $body .= "<div class=\"frontpage-blog-executive-summary\">";
                    $body .= "<div class=\"frontpage-blog-executive-icon\"><img src=\"". user_icon_html($post->weblog,100,true) ."\" align=\"left\" class=\"usericon\" /></div>";
                    $body .= "<h4>" . $post->title . "</h4>";
                    $body .= "<p class=\"frontpage-blog-date\">" . strftime("%B %d, %Y",$post->posted) . "</p>";
                    $postbodyarray = explode(" ", preg_replace( "|\w{3,10}://[\w\.\-_]+(:\d+)?[^\s\"\'<>\(\)\{\}]*|", "", strip_tags($post->body)), 30);
                    $body .= "<p class=\"frontpage-blog-content\">" . implode(" ", array_slice($postbodyarray,0,sizeof($postbodyarray) - 1)) . " ...</p>";
                    $body .= "<p class=\"frontpage-blog-from\">" . __gettext("From:") . " <a href=\"{$CFG->wwwroot}" . user_info("username",$post->weblog) . "\">" . user_info("name",$post->weblog) . "</a> - ";
                    $body .= "<a href=\"{$CFG->wwwroot}" . user_info("username",$post->weblog) . "/weblog/" . $post->ident . ".html\">" . __gettext("Read more") . "</a></p>";
                    $body .= "</div>";
                }
            }
            
            return $body;
        }

function blog_page_owner() {

  $weblog_name= optional_param('weblog_name');
  if (!empty ($weblog_name)) {
    return (int) user_info_username('ident', $weblog_name);
  }

}

// Removes all widgets for a user

function blog_user_delete($object_type, $event, $object) {

  global $CFG, $data;
  if (!empty ($object->ident) && $object_type == "user" && $event == "delete") {
    if ($posts= get_records_sql("select * from {$CFG->prefix}weblog_posts where owner = {$object->ident} or weblog = {$object->ident}")) {
      foreach ($posts as $post_info) {
        $post_info= plugin_hook("weblog_post", "delete", $post_info);
        if (!empty ($post_info)) {
          delete_records('weblog_posts', 'ident', $post_info->ident);
          delete_records('weblog_comments', 'post_id', $post_info->ident);
          delete_records('weblog_watchlist', 'weblog_post', $post_info->ident);
          delete_records('tags', 'tagtype', 'weblog', 'ref', $post_info->ident);
        }
      }
    }
    execute_sql("update {$CFG->prefix}weblog_comments set owner = -1 where owner = {$object->ident}", false);
    delete_records('weblog_watchlist', 'owner', $object->ident);
  }
  return $object;

}

/**
 * Function that extends the display_input_field functionality for support:<br>
 * <ul>
 *  <li>the 'select' input field that receives an assosiative array as parameter</li>
 * </ul>
 * @param array $parameter an array where:
 *                      0 => input name to display (for forms etc)
 *                      1 => data
 *                      2 => type of input field
 *                      3 => reference name (for tag fields and so on)
 *                      4 => ID number (if any)
 *                      5 => Owner
 *                      6 => Array()
 *@return string the string that represent the specified input type
 */
function blog_display_input_field($parameter) {
  $cleanid= $parameter[0];

  switch ($parameter[2]) {

  case "vertical_radio":
    foreach($parameter[6] as $option){
      $run_result .= "<input type=\"radio\" name=\"".$parameter[0]."\" value=\"$option\" id=\"".$cleanid."\" ";
      if($parameter[1]==$option){$run_result .= " checked ";}
      $run_result .= " />&nbsp;".$option."&nbsp;&nbsp;";
    }
  break;

  case "radio":
    foreach($parameter[6] as $option){
      $run_result .= "<input type=\"radio\" name=\"".$parameter[0]."\" value=\"$option\" id=\"".$cleanid."\" ";
      if($parameter[1]==$option){$run_result .= " checked ";}
      $run_result .= " />".$option."<br>";
    }
  break;

  case "select":
    $run_result .= "<select name=\"".$parameter[0]."\" id=\"".$cleanid."\" />";
    foreach($parameter[6] as $option){
      $run_result .="<option value=\"".htmlspecialchars(stripslashes($option), ENT_COMPAT, 'utf-8')."\" ";
      if($parameter[1]==$option){$run_result .= " selected ";}
      $run_result .= " >$option</option>";
    }
    $run_result .="</select><br>";
  break;

  case "selectg":
    $run_result .= "<select name=\"".$parameter[0]."\" id=\"".$cleanid."\" />";
    foreach($parameter[6] as $optiong => $grp){
      $run_result .="<optgroup label=\"".htmlspecialchars(stripslashes($optiong), ENT_COMPAT, 'utf-8')."\" />";
      foreach($grp as $option){
        $run_result .="<option value=\"".htmlspecialchars(stripslashes($optiong.", $option $optiong"), ENT_COMPAT, 'utf-8')."\" ";
        if($parameter[1]=="$option $optiong"){$run_result .= " selected ";}
        $run_result .= " >$option</option>
        ";
      }
      $run_result .="</optgroup><br>";
    }
    $run_result .="</select><br>";
  break;

  case "date_select":
  case "selectd":
    $par = explode("/",$parameter[1]);
    $run_result .= "
    <script language=\"JavaScript\">
    function select_date(form){
    var date = \"\"+form.dd.value+\"/\"+form.mm.value+\"/\"+form.aaaa.value;
    form.$cleanid.value = date;
    }
    </script>";
    for($i=0;$i<31;$i++){
      $data['dd'][] = ($i+1);
    }
    for($i=0;$i<12;$i++){
      $data['mm'][] = ($i+1);
    }
    $ya = getdate();
    for($i=0;$i<100;$i++){
      $data['aaaa'][] = (($ya['year']-99)+$i);
    }
    $i = 0;
    foreach($data as $key=>$option){
      $run_result .= "$key&nbsp;<select name=\"".$key."\"  onchange=\"select_date(this.form)\"  />";
      foreach($option as $valor){
        $run_result .="<option value=\"".htmlspecialchars(stripslashes($valor), ENT_COMPAT, 'utf-8')."\" ";
        if($par[$i]==$valor){$run_result .= " selected ";}
        $run_result .= " >$valor</option>\n";
      }
      $run_result .="</select>&nbsp;";
      $i++;
    }
    $run_result .="
    <input type=\"hidden\" name=\"".$parameter[0]."\" value=\"".$parameter[1]."\" id=\"".$cleanid."\" >";
  break;

  }
  return $run_result;
}

function blog_validate_input_field(){

}
?>