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
		
		$output = "";
		$trackmaxtime = 0;
		
		if (isset($page_owner)) {
			
			$output .= <<< END
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
				$output .= <<< END
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
						$trackmaxtime = max($trackmaxtime, $entry->posted);
						$link = url . $username . "/weblog/" . $entry->ident . ".html";
						$body = (run("weblogs:text:process",stripslashes($entry->body)));
						$output .= <<< END
		<item>
			<title>$title</title>
			<link>$link</link>
			<description>$body</description>
		</item>
END;
					}
				}
				$output .= <<< END
	</channel>
</rss>
END;
			}
			
			if ($output) {
				header("Pragma: public");
				header("Cache-Control: public"); 
				header('Expires: ' . gmdate("D, d M Y H:i:s", (time()+3600)) . " GMT");
				
				$if_modified_since = preg_replace('/;.*$/', '', $_SERVER['HTTP_IF_MODIFIED_SINCE']);
				$if_none_match = preg_replace('/[^0-9a-f]/', '', $_SERVER['HTTP_IF_NONE_MATCH']);
				
				if (!$trackmaxtime) {
					$trackmaxtime = time();
				}
				
				$lm = gmdate("D, d M Y H:i:s", $trackmaxtime) . " GMT";
				$etag = md5($output);
				
				if ($if_modified_since == $lm) {
					header("{$_SERVER['SERVER_PROTOCOL']} 304 Not Modified");
					exit;
				}
				if ($if_none_match == $etag) {
					header("{$_SERVER['SERVER_PROTOCOL']} 304 Not Modified");
					exit;
				}
				
				// Send last-modified header to enable if-modified-since requests
				if ($tstamp < time()) {
					header("Last-Modified: " . $lm);
				}
				
				header("Content-Length: " . strlen($output));
				header('ETag: "' . $etag . '"');
				
				header("Content-type: text/xml");
				echo $output;
			}
			
			
		}