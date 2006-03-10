<?php

	//	ELGG weblog RSS 2.0 page
	// this is now only used for tag-search feeds

	// Run includes
	require("../includes.php");
	
	run("profile:init");
	run("friends:init");
	run("weblogs:init");
	
	global $page_owner;
	
	if (isset($page_owner)) {
		
		$username = run("users:id_to_name", $page_owner);
		if ($username) {
/*			
			if (!isset($_REQUEST['tag']) || trim($_REQUEST['tag'])=="" ) {
				// no tag, serve plain file
				
				$publish_folder = substr($username,0,1);
				$rssfile = path . "_rss/data/" . $publish_folder . "/" . $username . "/weblog.xml";
				$rssurl = url . $username . "/weblog/rss2/";
				
				if (!file_exists($rssfile)) {
					// to allow for upgrading from pre-static version
					$rssresult = run("weblogs:rss:publish", array($page_owner, false));
				}
				header("{$_SERVER['SERVER_PROTOCOL']} 301 Moved Permanently");
				header("Location: $rssurl");
				exit;
				
			} else {
				// a tag has been set
				// not using static file for tags, because number of tags * number of users...
*/				
				$sitename = sitename;
				
				$output = "";
				
				$tag = trim($_REQUEST['tag']);
				
				$rssweblog = sprintf(gettext("Weblog items tagged with %s"),$tag);
				
				$info = db_query("select * from users where ident = $page_owner");
				if (sizeof($info) > 0) {
					$info = $info[0];
					$name = (stripslashes($info->name));
					$url = url;
					$username = (stripslashes($info->username));
					$mainurl = (url . $username . "/weblog/");
					$rssurl = $mainurl . "rss/" . urlencode(trim($_REQUEST['tag']));
					$rssdescription = sprintf(gettext("The weblog for %s, hosted on %s."),$name,$sitename);

					/* <?xml-stylesheet type="text/xsl" href="{$url}_rss/styles.xsl?url=$mainurl&rssurl=$rssurl"?> */
					/* <?xml-stylesheet type="text/xsl" href="{$rssurl}/rssstyles.xsl"?> */
					$output .= <<< END
<?xml-stylesheet type="text/xsl" href="{$rssurl}/rssstyles.xsl"?>

<rss version='2.0'   xmlns:dc='http://purl.org/dc/elements/1.1/'>					
	<channel xml:base='$mainurl'>
		<title><![CDATA[$name : $rssweblog]]></title>
		<description><![CDATA[$rssdescription]]></description>
		<link>$mainurl</link>
END;

					if (isset($_REQUEST['modifier']) && $_REQUEST['modifier'] == "not") {
						$output .= run("weblogs:rss:getitems", array($page_owner, 10, $tag,"not"));
					} else {
						$output .= run("weblogs:rss:getitems", array($page_owner, 10, $tag,""));
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
				
//			}
		}
	}
