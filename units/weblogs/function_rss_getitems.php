<?php
/*
 *	Function to get RSS item blocks for a weblog
 *	
 *	$parameter[0] is the numeric id of the user to retrieve
 *	$parameter[1] is the number of entries to retrieve
 *	$parameter[2] is a tag to search for, if any
 *
 */

	$run_result = "";
	
	if (isset($parameter) && is_array($parameter)) {
		
		$userid = (int) $parameter[0];
		if ($userid > 0) {
			$username = run("users:id_to_name", $userid);
		}
		if ($username) {
			
			$numrows = (int) $parameter[1];
			if (!$numrows) {
				$numrows = 10;
			}
			
			$tag = trim($parameter[2]);
			if (isset($parameter[3]) && $parameter[3] == "not") {
				$entries = db_query("select * from weblog_posts where weblog = $userid and weblog_posts.access = 'PUBLIC' and 0 = (select count(ident) from tags where tagtype = 'weblog' and tag = 'elgg' and tags.ref = weblog_posts.ident) order by weblog_posts.posted desc limit $numrows");
			} else if ($tag) {
				$entries = db_query("select weblog_posts.* from tags join weblog_posts on weblog_posts.ident = tags.ref where weblog_posts.weblog = $userid and weblog_posts.access = 'PUBLIC' and tags.tag = '$tag' and tags.tagtype = 'weblog' order by weblog_posts.posted desc limit " . $numrows);
			} else {
				$entries = db_query("select * from weblog_posts where weblog = $userid and access = 'PUBLIC' order by posted desc limit " . $numrows);
			}
			
			if (is_array($entries) && sizeof($entries) > 0) {
				foreach($entries as $entry) {
					$title = (stripslashes($entry->title));
					$link = url . $username . "/weblog/" . $entry->ident . ".html";
					$body = (run("weblogs:text:process",stripslashes($entry->body)));
					$pubdate = gmdate("D, d M Y H:i:s T", $entry->posted);
					$keywords = db_query("select * from tags where tagtype = 'weblog' and ref = '".$entry->ident."'");
					$keywordtags = "";
					if (sizeof($keywords) > 0) {
						foreach($keywords as $keyword) {
							$keywordtags .= "\n\t\t<dc:subject><![CDATA[".(stripslashes($keyword->tag)) . "]]></dc:subject>";
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
			} else {
				//$run_result .= "no items";
			}
			
		} // if ($username)
		
	}

?>