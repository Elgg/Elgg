<?php

	global $search_exclusions;
	
       $weblogPosted = gettext("Weblog posts by"); // gettext variable
       $inCategory = gettext("in category"); // gettext variable
       $rssForBlog = gettext("RSS feed for weblog posts by"); // gettext variable
       $otherUsers = gettext("Other users with weblog posts in category"); // gettext variable
       $otherUsers2 = gettext("Users with weblog posts in category"); // gettext variable

   



	if (isset($parameter) && $parameter[0] == "weblog" || $parameter[0] == "weblogall") {
		
		if ($parameter[0] == "weblog") {
			$search_exclusions[] = "weblogall";
			$owner = (int) $_REQUEST['owner'];
			$searchline = "tagtype = 'weblog' and owner = $owner and tag = '".addslashes($parameter[1])."'";
			$searchline = "(" . run("users:access_level_sql_where",$_SESSION['userid']) . ") and " . $searchline;
			$searchline = str_replace("owner","tags.owner",$searchline);
			$refs = db_query("select ref from tags where $searchline");
			$searchline = "";
			if (sizeof($refs) > 0) {
	
				foreach($refs as $ref) {
					if ($searchline != "") {
						$searchline .= " or ";
					}
					$searchline .= "weblog_posts.ident = " . $ref->ref;
				}
				$posts = db_query("select users.name, users.username, weblog_posts.title, weblog_posts.ident, weblog_posts.weblog, weblog_posts.owner, weblog_posts.posted from weblog_posts left join users on users.ident = weblog_posts.owner where ($searchline) order by posted desc");
				$run_result .= "<h2>$weblogPosted " . stripslashes($posts[0]->name) . " $inCategory '".$parameter[1]."'</h2>\n<ul>";
				foreach($posts as $post) {
					$run_result .= "<li>";
					$weblogusername = run("users:id_to_name",$post->weblog);
					$run_result .= "<a href=\"".url . $weblogusername . "/weblog/" . $post->ident . ".html\">" . gmdate("F d, Y",$post->posted) . " - " . stripslashes($post->title) . "</a>\n";
					if ($post->owner != $post->weblog) {
						$run_result .= " @ " . "<a href=\"" . url . $weblogusername . "/weblog/\">" . $weblogusername . "</a>\n";
					}
					$run_result .= "</li>";
				}
				$run_result .= "</ul>";
				$run_result .= "<p><small>[ <a href=\"".url.$post->username . "/weblog/rss/" . $parameter[1] . "\">$rssForBlog " . stripslashes($posts[0]->name) . " $inCategory '".$parameter[1]."'</a> ]</small></p>\n";
			}
		}
		$searchline = "tagtype = 'weblog' and tag = '".addslashes($parameter[1])."'";
		$searchline = "(" . run("users:access_level_sql_where",$_SESSION['userid']) . ") and " . $searchline;
		$searchline = str_replace("owner","tags.owner",$searchline);
		$sql = "select distinct users.* from tags left join users on users.ident = tags.owner where ($searchline)";
		if ($parameter[0] == "weblog") {
			$sql .= " and users.ident != " . $owner;
		}
		$users = db_query($sql);
		
		if (sizeof($users) > 0) {
			if ($parameter[0] == "weblog") {
				$run_result .= "<h2>$otherUsers '".$parameter[1]."'</h2>\n";
			} else {
				$run_result .= "<h2>$otherUsers2 '".$parameter[1]."'</h2>\n";
			}
			$body = "<table><tr>";
			$i = 1;
			foreach($users as $key => $info) {
	
					// $info = $info[0];
					if ($info->icon != -1) {
						$icon = db_query("select filename from icons where ident = " . $info->icon . " and owner = " . $info->ident);
						if (sizeof($icon) == 1) {
							$icon = $icon[0]->filename;
						} else {
							$icon = "default.png";
						}
					} else {
						$icon = "default.png";
					}
					list($width, $height, $type, $attr) = getimagesize(path . "_icons/data/" . $icon);
					if (sizeof($users) > 4) {
						$width = round($width / 2);
						$height = round($height / 2);
					}
		$friends_userid = $info->ident;
		$friends_name = htmlentities(stripslashes($info->name));
		$friends_menu = run("users:infobox:menu",array($info->ident));
		$link_keyword = urlencode($parameter[1]);
              $width = round($width / 2);
		$height = round($height / 2);
		$url = url;
		$body .= <<< END
		<td align="center">
                    <p>
			<a href="{$url}search/index.php?weblog={$link_keyword}&owner={$friends_userid}">
			<img src="{$url}_icons/data/{$icon}" width="{$width}" height="{$height}" alt="{$friends_name}" border="0" /></a><br />
			<span class="userdetails">
				{$friends_name}
				{$friends_menu}
			</span>
                    </p>
		</td>
END;
					if ($i % 5 == 0) {
						$body .= "\n</tr><tr>\n";
					}
					$i++;
			}
			$body .= "</tr></table>";
			$run_result .= $body;
		}
		
	}

?>