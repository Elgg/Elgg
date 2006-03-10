<?php
/*
 *	Function to publish weblog posts as RSS, either a static file or return output
 *	
 *	$parameter[0] is the numeric id of the user to publish
 *	
 *	$parameter[1] is true to return a string of RSS, or false to publish static file.
 *		(Defaults to publishing file)
 *
 */

	$run_result = false;
	
	if (isset($parameter) && is_array($parameter)) {
		
		$userid = (int) $parameter[0];
		if ($userid > 0) {
			$username = run("users:id_to_name", $userid);
		}
		if ($username) {
			
			// make output dirs if they don't already exist
			$publish_folder = substr($username,0,1);
			
			if (!file_exists(path . "_rss/data/" . $publish_folder)) {
				mkdir(path . "_rss/data/" . $publish_folder);
			}
			
			if (!file_exists(path . "_rss/data/" . $publish_folder . "/" . $username)) {
				mkdir(path . "_rss/data/" . $publish_folder . "/" . $username);
			}
			
			$rssfile = path . "_rss/data/" . $publish_folder . "/" . $username . "/files.xml";
			
			//generate rss
			$sitename = sitename;
			$rssfiles = gettext("Files");
			
			
			$info = db_query("select * from users where ident = $userid");
			
			$info = $info[0];
			$name = (stripslashes($info->name));
			$username = (stripslashes($info->username));
			$mainurl = (url . $username . "/files/");
			$rssurl = $mainurl . "rss/";
			$url = url;
			$rssdescription = sprintf(gettext("Files for %s, hosted on %s."),$name,$sitename);
			$output .= <<< END
<?xml-stylesheet type="text/xsl" href="{$rssurl}/rssstyles.xsl"?>
<rss version='2.0'   xmlns:dc='http://purl.org/dc/elements/1.1/'>
	<channel xml:base='$mainurl'>
		<title><![CDATA[$name : $rssfiles]]></title>
		<description><![CDATA[$rssdescription]]></description>
		<link>$mainurl</link>
END;
			
			// WEBLOGS
			$output .= run("files:rss:getitems", array($userid, 10, ""));
			
			$output .= <<< END

	</channel>
</rss>
END;
			
			if ($parameter[1] === true) {
				
				$run_result = $output;
				
			} else {
				
				// write to file
				if ($handle = fopen($rssfile, "wb")) {
					$writeresult = fwrite($handle, $output);
					$closeresult = fclose($handle);
					if ($writeresult && $closeresult) {
						$run_result = true;
					}
				}
				
			}
			
		} // if ($username)
		
	}

?>