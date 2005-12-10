<?php

	global $search_exclusions;

	if (isset($parameter) && $parameter[0] == "weblog") {
		
		$sitename = sitename;
		$url = url;
		
		$owner = (int) $_REQUEST['owner'];
		$searchline = "tagtype = 'weblog' and tag = '".addslashes($parameter[1])."'";
		$searchline = "(" . run("users:access_level_sql_where",$_SESSION['userid']) . ") and " . $searchline;
		$searchline = str_replace("access", "weblog_posts.access", $searchline);
		$searchline = str_replace("owner", "weblog_posts.weblog", $searchline);
		$refs = db_query("select distinct weblog_posts.owner from tags left join weblog_posts on weblog_posts.ident = ref left join users on users.ident = tags.owner where $searchline order by weblog_posts.posted desc limit 500");
		if (sizeof($refs) > 0) {
			foreach($refs as $post) {

				$page_owner = $post->owner;
				
				$run_result .= <<< END
<rss version='2.0'   xmlns:dc='http://purl.org/dc/elements/1.1/'>
END;
			$info = db_query("select * from users where ident = $page_owner");
			if (sizeof($info) > 0) {
				$info = $info[0];
				$name = htmlentities(stripslashes($info->name));
				$username = htmlentities(stripslashes($info->username));
				$mainurl = htmlentities(url . $username . "/weblog/");
				$run_result .= <<< END
  <channel xml:base='$mainurl'>
    <title>$name : Weblog</title>
    <description>The weblog for $name, hosted on $sitename.</description>
    <language>en-gb</language>
    <link>$mainurl</link>
END;
				if (!isset($_REQUEST['tag'])) {
					$entries = db_query("select * from weblog_posts where weblog = $page_owner and access = 'PUBLIC' order by posted desc limit 10");
				} else {
					$tag = addslashes($_REQUEST['tag']);
					$entries = db_query("select weblog_posts.* from tags left join weblog_posts on weblog_posts.ident = tags.ref where weblog_posts.weblog = $page_owner and weblog_posts.access = 'PUBLIC' and tags.tag = '$tag' and tags.tagtype = 'weblog' order by weblog_posts.posted desc limit 10");
				}
				if (sizeof($entries) > 0) {
					foreach($entries as $entry) {
						$title = htmlentities(stripslashes($entry->title));
						$link = url . $username . "/weblog/" . $entry->ident . ".html";
						$body = htmlentities(run("weblogs:text:process",stripslashes($entry->body)));
						$pubdate = gmdate("D, d M Y H:i:s T", $entry->posted);
						$keywords = db_query("select * from tags where tagtype = 'weblog' and ref = '".$entry->ident."'");
						$keywordtags = "";
						if (sizeof($keywords) > 0) {
							foreach($keywords as $keyword) {
								$keywordtags .= "\n        <dc:subject><![CDATA[".htmlentities(stripslashes($keyword->tag)) . "]]></dc:subject>";
							}
						}
						$run_result .= <<< END
    <item>
        <title><![CDATA[$title]]></title>
        <link>$link</link>
        <pubDate>$pubdate</pubDate>$keywordtags
        <description><![CDATA[$body]]></description>
    </item>
END;
					}
				}
				$run_result .= <<< END
  </channel>
</rss>
END;
		}
								
			}
		}
	}

?>