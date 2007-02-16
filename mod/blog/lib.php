<?php

function blog_pagesetup() {
    // register links -- 
    global $profile_id;
    global $PAGE;
    global $CFG;

    $page_owner = $profile_id;

    // main menu
    if (isloggedin() && user_info("user_type",$_SESSION['userid']) != "external") {

        if (defined("context") && context == "weblog" && $page_owner == $_SESSION['userid']) {
            
            $PAGE->menu[] = array( 'name' => 'weblog',
                                   'html' => "<li><a href=\"{$CFG->wwwroot}{$_SESSION['username']}/weblog\" class=\"selected\" >" .__gettext("Your Blog").'</a></li>');
            
        } else {
            $PAGE->menu[] = array( 'name' => 'weblog',
                                   'html' => "<li><a href=\"{$CFG->wwwroot}{$_SESSION['username']}/weblog\" >" .__gettext("Your Blog").'</a></li>');
        };
    }

    $weblog_username = user_info('username', $page_owner);
    
    // submenu
    if (defined("context") && context == "weblog") {
        
        if ($page_owner != -1) {

            $PAGE->menu_sub[] = array ( 'name' => 'blog:rssfeed',
                                        'html' => "<a href=\"{$CFG->wwwroot}{$weblog_username}/weblog/rss/\"><img src=\"{$CFG->wwwroot}mod/template/icons/rss.png\" border=\"0\" alt=\"rss\" /></a>"); 

            
            if (run("permissions:check", "weblog") && logged_on) {
                               $PAGE->menu_sub[] = array ( 'name' => 'blog:post',
                                           'html' => "<a href=\"{$CFG->wwwroot}_weblog/edit.php?owner=$page_owner\">"
                                           . __gettext("Post a new entry") . '</a>');
            }

            $PAGE->menu_sub[] = array ( 'name' => 'blog:view',
                                        'html' => "<a href=\"{$CFG->wwwroot}{$weblog_username}/weblog/\">"
                                        . __gettext("View blog") . '</a>');

            $PAGE->menu_sub[] = array ( 'name' => 'blog:archive',
                                        'html' => "<a href=\"{$CFG->wwwroot}{$weblog_username}/weblog/archive/\">"
                                        . __gettext("Archive") . '</a>'); 

            $PAGE->menu_sub[] = array ( 'name' => 'blog:friends',
                                        'html' => "<a href=\"{$CFG->wwwroot}{$weblog_username}/weblog/friends/\">"
                                        . __gettext("Friends' blogs") . '</a>'); 
            
            if ($page_owner == $_SESSION['userid']) {
                $PAGE->menu_sub[] = array ( 'name' => 'blog:interesting',
                                            'html' => "<a href=\"{$CFG->wwwroot}{$weblog_username}/weblog/interesting/\">"
                                            . __gettext("Interesting posts") . '</a>'); 
            }
        }
        
        $PAGE->menu_sub[] = array ( 'name' => 'blog:everyone',
                                    'html' => "<a href=\"{$CFG->wwwroot}_weblog/everyone.php\">"
                                    . __gettext("View all posts") . '</a>'); 

    }

}

        function blog_init() {
            
            global $CFG, $function;
            
            $CFG->widgets->display['blog'] = "blog_widget_display";
            $CFG->widgets->edit['blog'] = "blog_widget_edit";
            $CFG->widgets->list[] = array(
                                                'name' => __gettext("Blog widget"),
                                                'description' => __gettext("Displays the latest blog posts from a blog of your choice."),
                                                'id' => "blog"
                                        );
            $CFG->templates->variables_substitute['blog'][] = "blog_keyword";
            $CFG->templates->variables_substitute['blogsummary'][] = "blog_summary_keyword";
            
        }
        
        function blog_widget_display($widget) {
            
            global $CFG;
            
            $blog_id = adash_get_data("blog_id",$widget->ident);
            $blog_posts = adash_get_data("blog_posts",$widget->ident);
            
            $body = "";
            
            if (empty($blog_id)) {
                global $page_owner;
                $blog_id = $page_owner;
            }
            if (empty($blog_posts)) {
                $blog_posts = 1;
            }
            
            
            $where = run("users:access_level_sql_where",$_SESSION['userid']);
            $posts = get_records_sql("select * from ".$CFG->prefix."weblog_posts where ($where) and weblog = $blog_id order by posted desc limit $blog_posts");
            
            if (is_array($posts) && !empty($posts)) {
                foreach($posts as $post) {
                    $body .= run("weblogs:posts:view",$post);
                }
            }
            
            return $body;
            
        }
        
        function blog_widget_edit($widget) {
            
            global $CFG, $page_owner;
            
            $blog_id = adash_get_data("blog_id",$widget->ident);
            $blog_posts = adash_get_data("blog_posts",$widget->ident);
            if (empty($blog_posts)) {
                $blog_posts = 1;
            }
            if (empty($blog_id)) {
                $blog_id = $page_owner;
            }
            
            $connections = get_records_sql("select u.ident, u.name from ".$CFG->prefix."friends f join ".$CFG->prefix."users u on u.ident = f.friend where f.owner = " . $_SESSION['userid'] . " order by u.name asc");
            $data = new stdClass;
            $data->ident = $page_owner;
            $data->name = run("profile:display:name", $page_owner);
            $connections[] = $data;
            if ($page_owner != $_SESSION['userid']) {
                $data = new stdClass;
                $data->ident = $_SESSION['userid'];
                $data->name = run("profile:display:name", $_SESSION['userid']);
                $connections[] = $data;
            }

            $body = "<h2>" . __gettext("Blog dashboard widget") . "</h2>";
            $body .= "<p>" . __gettext("This widget displays the last couple of blog posts from an individual user. To begin, select the user from your connections below:") . "</p>";

            $body .= "<p><select name=\"dashboard_data[blog_id]\">\n";
            if (is_array($connections) && !empty($connections)) {
                foreach ($connections as $connection) {
                    if ($connection->ident == $blog_id) {
                        $selected = "selected=\"selected\"";
                    } else {
                        $selected = "";
                    }
                    $body .= "<option value=\"" . $connection->ident . "\" $selected>" . $connection->name . "</option>\n";
                }
            }
            $body .= "</select></p>\n";
                        
            $body .= "<p>" . __gettext("Then enter the number of blog posts you'd like to display:") . "</p>";
            
            $body .= "<p><input type=\"text\" name=\"dashboard_data[blog_posts]\" value=\"" . $blog_posts . "\" /></p>";
            
            return $body;
            
        }
        
        function blog_keyword($vars) {
            global $CFG, $db;
            
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
                    if ($vars[3] != "slim") {
                        $body .= run("weblogs:posts:view",$post);
                    } else {
                        $body .= "<div class=\"frontpage-blog-contents\">";
                        $body .= "<h4>" . $post->title . "</h4>";
                        $body .= "<p class=\"frontpage-blog-date\">" . strftime("%B %d, %Y",$post->posted) . "</p>";
                        $body .= "<p class=\"frontpage-blog-body\">" . run("weblogs:text:process", $post->body) . "</p>";
                        $body .= "<p class=\"frontpage-blog-from\">" . __gettext("From:") . " <a href=\"{$CFG->wwwroot}" . user_info("username",$post->weblog) . "\">" . user_info("name",$post->weblog) . "</a> - ";
                        $body .= "<a href=\"{$CFG->wwwroot}" . user_info("username",$post->weblog) . "/weblog/" . $post->ident . ".html\">" . __gettext("Read more") . "</a></p>";
                        $body .= "</div>";
                    }
                }
            }
            
            return $body;
        }
        
        function blog_summary_keyword($vars) {
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
                    $body .= "<div class=\"frontpage-blog-summary\">";
                    $body .= "<h4>" . $post->title . "</h4>";
                    $body .= "<p class=\"frontpage-blog-date\">" . strftime("%B %d, %Y",$post->posted) . "</p>";
                    $body .= "<p class=\"frontpage-blog-from\">" . __gettext("From:") . " <a href=\"{$CFG->wwwroot}" . user_info("username",$post->weblog) . "\">" . user_info("name",$post->weblog) . "</a> - ";
                    $body .= "<a href=\"{$CFG->wwwroot}" . user_info("username",$post->weblog) . "/weblog/" . $post->ident . ".html\">" . __gettext("Read more") . "</a></p>";
                    $body .= "</div>";
                }
            }
            
            return $body;
        }
        
    function blog_page_owner() {
        
        $weblog_name = optional_param('weblog_name');
        if (!empty($weblog_name)) {
            return (int) user_info_username('ident', $weblog_name);
        }
        
    }
    
?>