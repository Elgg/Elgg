<?php

	//	ELGG files RSS 2.0 page

	// Run includes
		require("../includes.php");
		
		run("profile:init");
		run("friends:init");
		run("files:init");
		
		global $profile_id;
		global $individual;
		global $page_owner;
		
		$individual = 1;
		
		$sitename = htmlentities(sitename);
		
		header("Content-type: text/xml");
		
		$rssfiles = gettext("Files");
		
		if (isset($profile_id)) {
			
			echo <<< END
<rss version='2.0'   xmlns:dc='http://purl.org/dc/elements/1.1/'>

END;
			$info = db_query("select * from users where ident = $page_owner");
			if (sizeof($info) > 0) {
				$info = $info[0];
				$name = (stripslashes($info->name));
				$username = (stripslashes($info->username));
				$mainurl = (url . $username . "/files/");
				$rssdescription = sprintf(gettext("Files for %s, hosted on %s."),$name,$sitename);
				echo <<< END
  <channel xml:base='$mainurl'>
    <title><![CDATA[$name : $rssfiles]]></title>
    <description><![CDATA[$rssdescription]]></description>
    <link>$mainurl</link>
END;
				if (!isset($_REQUEST['tag'])) {
					$files = db_query("select * from files where files_owner = $page_owner and access = 'PUBLIC' order by time_uploaded desc limit 10");
				} else {
					$tag = addslashes($_REQUEST['tag']);
					$files = db_query("select files.* from tags left join files on files.ident = tags.ref where files.files_store = $page_owner and files.access = 'PUBLIC' and tags.tagtype = 'file' and tags.tag = '$tag' order by files.time_uploaded desc limit 10");
				}
				if (sizeof($files) > 0) {
					foreach($files as $file) {
						$title = htmlentities(stripslashes($file->title));
						$link = url . $username . "/files/" . $file->folder . "/" . $file->ident . "/" . (urlencode(stripslashes($file->originalname)));
						$description = (stripslashes($file->description));
						$pubdate = gmdate("D, d M Y H:i:s T", $file->time_uploaded);
						$length = (int) $file->size;
						$mimetype = run("files:mimetype:determine",$file->location);
						if ($mimetype == false) {
							$mimetype = "application/octet-stream";
						}
						$keywords = db_query("select * from tags where tagtype = 'file' and ref = '".$file->ident."'");
						$keywordtags = "";
						if (sizeof($keywords) > 0) {
							foreach($keywords as $keyword) {
								$keywordtags .= "\n        <dc:subject><![CDATA[". (stripslashes($keyword->tag)) . "]]></dc:subject>";
							}
						}
						echo <<< END

    <item>
        <title><![CDATA[$title]]></title>
        <link>$link</link>
        <enclosure url="$link" length="$length" type="$mimetype" />
        <pubDate>$pubdate</pubDate>$keywordtags
        <description><![CDATA[$description]]></description>
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