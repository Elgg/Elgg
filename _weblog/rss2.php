<?php

	//	ELGG weblog RSS 2.0 page

	// Run includes
		require("../includes.php");
		
		run("profile:init");
		run("friends:init");
		run("weblogs:init");
		
		global $profile_id;
		global $individual;
		global $page_owner;
		
		$individual = 1;
		
		$sitename = (sitename);
		
		header("Content-type: text/xml");
		
		if (isset($page_owner)) {
			
			$rssweblog = gettext("Weblog");
			
			echo <<< END
<rss version='2.0'   xmlns:dc='http://purl.org/dc/elements/1.1/'>
END;
			$info = db_query("select * from users where ident = $page_owner");
			if (sizeof($info) > 0) {
				$info = $info[0];
				$name = (stripslashes($info->name));
				$username = (stripslashes($info->username));
				$mainurl = (url . $username . "/weblog/");
				$rssdescription = sprintf(gettext("The weblog for %s, hosted on %s."),$name,$sitename);
				echo <<< END
  <channel xml:base='$mainurl'>
    <title><![CDATA[$name : $rssweblog]]></title>
    <description><![CDATA[$rssdescription]]></description>
    <link>$mainurl</link>
END;
			// WEBLOGS

				if (!isset($_REQUEST['tag'])) {
					$entries = db_query("select * from weblog_posts where weblog = $page_owner and access = 'PUBLIC' order by posted desc limit 10");
				} else {
					$tag = addslashes($_REQUEST['tag']);
					$entries = db_query("select weblog_posts.* from tags left join weblog_posts on weblog_posts.ident = tags.ref where weblog_posts.weblog = $page_owner and weblog_posts.access = 'PUBLIC' and tags.tag = '$tag' and tags.tagtype = 'weblog' order by weblog_posts.posted desc limit 10");
				}
				if (sizeof($entries) > 0) {
					foreach($entries as $entry) {
						$title = (stripslashes($entry->title));
						$link = url . $username . "/weblog/" . $entry->ident . ".html";
						$body = (run("weblogs:text:process",stripslashes($entry->body)));
						$pubdate = gmdate("D, d M Y H:i:s T", $entry->posted);
						$keywords = db_query("select * from tags where tagtype = 'weblog' and ref = '".$entry->ident."'");
						$keywordtags = "";
						if (sizeof($keywords) > 0) {
							foreach($keywords as $keyword) {
								$keywordtags .= "\n        <dc:subject><![CDATA[".(stripslashes($keyword->tag)) . "]]></dc:subject>";
							}
						}
						echo <<< END
    <item>
        <title><![CDATA[$title]]></title>
        <link>$link</link>
        <pubDate>$pubdate</pubDate>$keywordtags
        <description><![CDATA[$body]]></description>
    </item>
END;
					}
				}
				echo <<< END
  </channel>
</rss>
END;
		}
	}