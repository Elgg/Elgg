<?php

	global $page_owner;
	
	// If this is someone else's portfolio, display the user's icon
		$run_result .= "<div class=\"box_user\">";
		
		$info = db_query("select * from users where ident = $page_owner");
		$info = $info[0];
		
		if ($info->icon != -1 && $info->icon != NULL) {
				$icon = db_query("select filename from icons where ident = " . $info->icon . " and owner = $page_owner");
				if (sizeof($icon) == 1) {
					$icon = $icon[0]->filename;
				} else {
					$icon = "default.png";
				}
		} else {
			$icon = "default.png";
		}
		
		$tagline = db_query("select * from profile_data where profile_data.owner = $page_owner and profile_data.name = 'minibio' and (" . run("users:access_level_sql_where",$_SESSION['userid']) . ")");
		
		if (sizeof($tagline) > 0) {
			$tagline = stripslashes($tagline[0]->value);
		} else {
			$tagline = "&nbsp;";
		}
		
		list($width, $height, $type, $attr) = getimagesize(path . "_icons/data/" . $icon);
		
		$width = round($width * (2/3));
		$height = round($height * (2/3));
		
		$icon = "<img src=\"".url."_icons/data/$icon\" width=\"$width\" height=\"$height\" />";
		$name = stripslashes($info->name);
		$url = url . stripslashes($info->username) . "/";
		
		$body = run("templates:draw", array(
							'context' => 'ownerbox',
							'name' => $name,
							'profileurl' =>  $url,
							'usericon' => $icon,
							'tagline' => $tagline,
							'usermenu' => run("users:infobox:menu:text",array($page_owner))
						)
						);
		
		if ($page_owner != -1) {
			if ($page_owner != $_SESSION['userid']) {
				$title = gettext("Profile Owner");
			} else {
				$title = gettext("You");
			}
		}
		
		$run_result .= run("templates:draw", array(
							'context' => 'contentholder',
							'title' => $title,
							'body' => $body,
							'submenu' => ""
							)
							);
		
		$run_result .= "</div>";

?>