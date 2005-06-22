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
<?xml version="1.0"?><!DOCTYPE rss SYSTEM "http://my.netscape.com/publish/formats/rss-0.91.dtd">

<rss version="0.91">
END;
			$info = db_query("select * from users where ident = $page_owner");
			if (sizeof($info) > 0) {
				$info = $info[0];
				$name = htmlentities(stripslashes($info->name));
				$username = htmlentities(stripslashes($info->username));
				$sitename = sitename;
				$mainurl = htmlentities(url . $username . "/weblog/");
				echo <<< END
  <channel>
    <title>$name : Weblog</title>
    <description>The weblog for $name, hosted on $sitename.</description>
    <language>en-gb</language>
    <link>$mainurl</link>
END;
				$entries = db_query("select * from weblog_posts where weblog = $page_owner and access = 'PUBLIC' order by posted desc limit 10");
				if (sizeof($entries) > 0) {
					foreach($entries as $entry) {
						$title = htmlentities(stripslashes($entry->title));
						$link = url . $username . "/weblog/" . $entry->ident . ".html";
						$body = htmlentities(run("weblogs:text:process",stripslashes($entry->body)));
						echo <<< END
    <item>
        <title>$title</title>
        <link>$link</link>
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