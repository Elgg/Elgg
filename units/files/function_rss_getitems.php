<?php
/*
 *	Function to get RSS item blocks for a filestore
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
			if ($tag) {
				$files = db_query("select files.* from tags join files on files.ident = tags.ref where files.files_owner = $userid and files.access = 'PUBLIC' and tags.tagtype = 'file' and tags.tag = '$tag' order by files.time_uploaded desc limit " . $numrows);
			} else {
				$files = db_query("select * from files where files_owner = $userid and access = 'PUBLIC' order by time_uploaded desc limit " . $numrows);
			}
			
			if (is_array($files) && sizeof($files) > 0) {
				foreach($files as $file) {
					$title = (stripslashes($file->title));
					$link = url . $username . "/files/" . $file->folder . "/" . $file->ident . "/" . (urlencode(stripslashes($file->originalname)));
					$description = (stripslashes($file->description));
					$pubdate = gmdate("D, d M Y H:i:s T", $file->time_uploaded);
					$trackmaxtime = max($trackmaxtime, $file->time_uploaded);
					$length = (int) $file->size;
					$mimetype = run("files:mimetype:determine",$file->location);
					if ($mimetype == false) {
						$mimetype = "application/octet-stream";
					}
					$keywords = db_query("select * from tags where tagtype = 'file' and ref = '".$file->ident."'");
					$keywordtags = "";
					if (sizeof($keywords) > 0) {
						foreach($keywords as $keyword) {
							$keywordtags .= "\n\t\t<dc:subject><![CDATA[". (stripslashes($keyword->tag)) . "]]></dc:subject>";
						}
					}
					$run_result .= <<< END

		<item>
			<title><![CDATA[$title]]></title>
			<link>$link</link>
			<enclosure url="$link" length="$length" type="$mimetype" />
			<pubDate>$pubdate</pubDate>$keywordtags
			<description><![CDATA[$description]]></description>
		</item>
END;
				}
			} else {
				//$run_result .= "no items";
			}
			
		} // if ($username)
		
	}

?>