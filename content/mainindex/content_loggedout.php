<?php

	$sitename = sitename;
	
	$run_result = "<h5>" . gettext("Welcome") . "</h5>";
	$run_result .= "<p>" . sprintf(gettext("This is %s, a learning landscape. Why not check out <a href=\"%s\">what people are saying</a> right now."), $sitename, url . "_weblog/everyone.php") . "<br />";
	$run_result .= "<p>". sprintf(gettext("<a href=\"%s\">Find others</a> with similar interests and goals."), url . "search/tags.php") . "<br /><br />";
	
	$users = db_query("SELECT distinct users.*, icons.filename as iconfile FROM tags LEFT JOIN users ON users.ident = tags.owner left join icons on icons.ident = users.icon WHERE (tags.tagtype = 'biography' OR tags.tagtype = 'minibio' OR tags.tagtype = 'interests')AND users.icon != -1 AND tags.access = 'PUBLIC' and users.user_type = 'person' ORDER BY rand( ) LIMIT 3 ");
	
	if (sizeof($users) > 0) {
		if (sizeof($users) > 1) {
			$run_result .= gettext("Here are some example users:");
		} else {
			$run_result .= gettext("Here is an example user:");
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
	
	$run_result .= "<p>" . sprintf(gettext("If you like what you see, why not <a href=\"%s\">register for an account</a>?"), url . "_invite/register.php") . "</p>";
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