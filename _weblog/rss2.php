<?php

	//	ELGG weblog RSS 0.91 page

	// Run includes
		require("../includes.php");
		
		run("profile:init");
		run("friends:init");
		run("weblogs:init");
		
		global $profile_id;
		global $individual;
		global $page_owner;
		
		$individual = 1;
		
		header("Content-type: text/xml");
		
		if (isset($page_owner)) {
			
			echo <<< END
<?xml version='1.0' encoding='UTF-8'?>
<rss version='2.0'   xmlns:dc='http://purl.org/dc/elements/1.1/'>
END;
			$info = db_query("select * from users where ident = $page_owner");
			if (sizeof($info) > 0) {
				$info = $info[0];
				$name = htmlentities(stripslashes($info->name));
				$username = htmlentities(stripslashes($info->username));
				$mainurl = htmlentities(url . $username . "/weblog/");
				echo <<< END
  <channel xml:base='$mainurl'>
    <title>$name : Weblog</title>
    <description>The weblog for $name, hosted on Elgg.</description>
    <language>en-gb</language>
    <link>$mainurl</link>
END;
				$entries = db_query("select * from weblog_posts where owner = $page_owner and access = 'PUBLIC' order by posted desc limit 10");
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
								$keywordtags .= "\n        <dc:subject>".htmlentities(stripslashes($keyword->tag)) . "</dc:subject>";
							}
						}
						echo <<< END
    <item>
        <title>$title</title>
        <link>$link</link>
        <pubDate>$pubdate</pubDate>$keywordtags
        <description>$body</description>
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