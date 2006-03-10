<?php

	//	ELGG aggregated RSS 2.0 page
	// this is now only used for tag-search feeds

	// Run includes
		require("../includes.php");
		
		run("weblogs:init");
		run("profile:init");
		
		global $individual;
		global $page_owner;
		
		$individual = 1;
		
		$sitename = (sitename);
		
		$output = "";
		
		$tag = trim($_REQUEST['tag']);
		
		if (isset($page_owner)) {
			
			$rssactivity = sprintf(gettext("Activity tagged with %s"),$tag);
			
			$info = db_query("select * from users where ident = $page_owner");
			if (sizeof($info) > 0) {
				$info = $info[0];
				$name = (stripslashes($info->name));
				$username = (stripslashes($info->username));
				$mainurl = (url . $username . "/");
				$rssurl = $mainurl . "rss/" . urlencode(trim($_REQUEST['tag']));
				$url = url;
				$rssdescription = sprintf(gettext("Activity for %s, hosted on %s."),$name,$sitename);
				$output .= <<< END
<?xml-stylesheet type="text/xsl" href="{$rssurl}/rssstyles.xsl"?>
<rss version='2.0'   xmlns:dc='http://purl.org/dc/elements/1.1/'>
	<channel xml:base='$mainurl'>
		<title><![CDATA[$name : $rssactivity]]></title>
		<description><![CDATA[$rssdescription]]></description>
		<link>$mainurl</link>
END;

				
				// WEBLOGS
				$output .= run("weblogs:rss:getitems", array($page_owner, 10, $tag));
				
				// FILES
				$output .= run("files:rss:getitems", array($page_owner, 10, $tag));
				
				$output .= <<< END

	</channel>
</rss>
END;
			}
			
			if ($output) {
				header("Pragma: public");
				header("Cache-Control: public"); 
				header('Expires: ' . gmdate("D, d M Y H:i:s", (time()+3600)) . " GMT");
				
				$if_none_match = preg_replace('/[^0-9a-f]/', '', $_SERVER['HTTP_IF_NONE_MATCH']);
				
				$etag = md5($output);
				header('ETag: "' . $etag . '"');
				
				if ($if_none_match == $etag) {
					header("{$_SERVER['SERVER_PROTOCOL']} 304 Not Modified");
					exit;
				}
				
				header("Content-Length: " . strlen($output));
				
				header("Content-type: text/xml");
				echo $output;
			}
		}