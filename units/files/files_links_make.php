<?php

	// Turn file ID into a proper link
	
		if (isset($parameter)) {
			
			$fileid = (int) $parameter;
			$file = db_query("select * from files where ident = $fileid");
			if (sizeof($file) > 0) {
				if (run("users:access_level_check",$file[0]->access) || $file[0]->owner == $_SESSION['userid']) {
					if (!in_array(run("files:mimetype:inline", path . $file[0]->location), $data['mimetype:inline'])) {
						if (run("files:mimetype:determine",path . $file[0]->location) == "audio/mpeg") {
							$filepath = url . run("users:id_to_name",$file[0]->owner) . "/files/" . $file[0]->folder . "/" . $file[0]->ident . "/" . $file[0]->originalname;
							$filetitle = urlencode(stripslashes($file[0]->title));
							$run_result .= "<object classid=\"clsid:d27cdb6e-ae6d-11cf-96b8-444553540000\"
codebase=\"http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0\"
width=\"400\" height=\"15\" >
	<param name=\"allowScriptAccess\" value=\"sameDomain\"/>
	<param name=\"movie\" value=\"" . url . "_files/mp3player/xspf_player_slim.swf?song_url=$filepath&amp;song_title=$filetitle\"/>
	<param name=\"quality\" value=\"high\"/>
	<param name=\"bgcolor\" value=\"#E6E6E6\"/>
	<embed src=\"" . url . "_files/mp3player/xspf_player_slim.swf?song_url=$filepath&amp;song_title=$filetitle\"
	quality=\"high\" bgcolor=\"#E6E6E6\" name=\"xspf_player\" allowscriptaccess=\"sameDomain\"
	type=\"application/x-shockwave-flash\"
	pluginspage=\"http://www.macromedia.com/go/getflashplayer\"
	align=\"center\" height=\"15\" width=\"400\"> </embed>
</object>";
						} else {
							$run_result .= "<a href=\"";
							$run_result .= url . run("users:id_to_name",$file[0]->owner) . "/files/" . $file[0]->folder . "/" . $file[0]->ident . "/" . $file[0]->originalname;
							$run_result .= "\" >";
							$run_result .= stripslashes($file[0]->title);
							$run_result .= "</a>";
						}
					} else {
						list($width, $height, $type, $attr) = @getimagesize(path . $file[0]->location);
						if ($width > 400 || $height > 400) {
							$run_result .= "<a href=\"";
							$run_result .= url . run("users:id_to_name",$file[0]->owner) . "/files/" . $file[0]->folder . "/" . $file[0]->ident . "/" . $file[0]->originalname;
							$run_result .= "\" >";
							$run_result .= stripslashes($file[0]->title);
							$run_result .= "</a>";
						} else {
							$run_result .= "<img src=\"";
							$run_result .= url . run("users:id_to_name",$file[0]->owner) . "/files/" . $file[0]->folder . "/" . $file[0]->ident . "/" . $file[0]->originalname;
							$run_result .= "\" $attr alt=\"".htmlentities(stripslashes($file[0]->title))."\" />";
						}
					}
				} else {
					$run_result .= "<b>[" . gettext("You do not have permission to access this file") . "]</b>";
				}
			} else {
				$run_result .= "<b>[" . gettext("File does not exist") . "]</b>";
			}
			
		}

?>