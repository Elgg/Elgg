<?php

	$sitename = sitename;
	
	$run_result = "<h5>" . gettext("Welcome") . "</h5>";
	$run_result .= "<p><b>" . sprintf(gettext("Why not <a href=\"%s\">create your profile</a>?"), url . "profile/edit.php") . "</b><br />";
	$run_result .= "<p>". gettext("Tell people about yourself and connect to others with similar interests and goals.") . "<br />";
	
	$users = db_query("SELECT distinct users.*, icons.filename as iconfile FROM tags LEFT JOIN users ON users.ident = tags.owner left join icons on icons.ident = users.icon WHERE (tags.tagtype = 'biography' OR tags.tagtype = 'minibio' OR tags.tagtype = 'interests')AND users.icon != -1 AND tags.access = 'PUBLIC' and users.user_type = 'person' ORDER BY rand( ) LIMIT 3 ");
	
	if (sizeof($users) > 0) {
		if (sizeof($users) > 1) {
			$run_result .= gettext("Here are some examples of complete profiles:");
		} else {
			$run_result .= gettext("Here is an example of a complete profile:");
		}
		foreach($users as $key => $user) {
			if ($key > 0) {
				$run_result .= ", ";
			} else {
				$run_result .= " ";
			}
			$run_result .= "<a href=\"" . url . $user->username . "/\">" . stripslashes($user->name) . "</a>";
		}
	}
	
	$run_result .= "</p>";
	
	$run_result .= "<p><b>" . sprintf(gettext("Or you could <a href=\"%s\">start your blog</a>?"),url . "_weblog/edit.php") . "</b><br /><br />";
    $run_result .= sprintf(gettext("Comment on what you're learning, collect interesting links and decide who gets to see what you're writing. Here's what <a href=\"%s\">everyone else is talking about</a> right now."),url . "_weblog/everyone.php") . "</p>";
    $run_result .= "<p>&nbsp;</p>";
    
    $news = db_query("select weblog_posts.* from weblog_posts left join users on users.ident = weblog_posts.weblog where users.username = 'news' order by posted desc limit 1");
    if (sizeof($news) > 0) {
    	
	    $news = $news[0];
	    
		$run_result .= "<div class=\"siteNews\">";
		$run_result .= "<h2>" . gettext("Latest news") . "</h2>";
		$run_result .= "<p>" . nl2br(stripslashes($news->body)) . "</p>";
		$run_result .= "</div>";
		
	}

?>