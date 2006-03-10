<?php

	global $profile_id;

	$url = url;
	
	// Given a title and series of user IDs as a parameter, will display a box containing the icons and names of each specified user
	// $parameter[0] is the title of the box; $parameter[1..n] is the user ID

	if (isset($parameter[0]) && sizeof($parameter) > 1 /*&& $parameter[1][0] != 0*/) {

		if (sizeof($parameter[1]) > 1) {
			$span = 2;
		} else {
			$span = 1;
		}
		
		$name = $parameter[0];
		
		$i = 1;
		if (sizeof($parameter[1]) == 0) {
			
			$body = "<p>" . gettext("None.") . "</p>";
			
			if (isset($parameter[2]) && $parameter[2] != "") {
				$body .= "<p>" . $parameter[2] . "</p>";
			}
			
		} else {
			$body .= <<< END
			
	<ul>
			
END;
			$icon = "default.png";
			$defaulticonparams = @getimagesize(path . "_icons/data/default.png");
			foreach($parameter[1] as $key => $ident) {
				list($width, $height, $type, $attr) = $defaulticonparams;
				$ident = (int) $ident;
				// if (!isset($_SESSION['user_info_cache'][$ident])) {
					$info = db_query("select * from users where ident = $ident");
					$_SESSION['user_info_cache'][$ident] = $info[0];
					$info = $info[0];
				// }
				$info = $_SESSION['user_info_cache'][$ident];
				if ($info->icon != -1 && $info->icon != NULL) {
					// if (!isset($_SESSION['icon_cache'][$info->icon]) || (time() - $_SESSION['icon_cache'][$info->icon]->created > 60)) {
						$icon = db_query("select filename from icons where ident = " . $info->icon . " and owner = $ident");
						//$_SESSION['icon_cache'][$info->icon]->created = time();
						if (sizeof($icon) == 1) {
							//$_SESSION['icon_cache'][$info->icon]->data = $icon[0]->filename;
							$icon = $icon[0]->filename;
							if (!(list($width, $height, $type, $attr) = @getimagesize(path . "_icons/data/" . $icon))) {
								$icon = "default.png";
								list($width, $height, $type, $attr) = $defaulticonparams;
							}
						}
					// }
					// $icon = $_SESSION['icon_cache'][$info->icon]->data;
				}
				
				if (sizeof($parameter[1]) > 1) {
					$width = round($width / 2);
					$height = round($height / 2);
				}

				$username = htmlentities(stripslashes($info->name));
				$usermenu = "";
				if ($info->ident == $profile_id || (logged_on && (!isset($profile_id) && $info->ident == $_SESSION['userid']))) {
					$rsslink = "<br /><a href=\"{$url}{$info->username}/rss/\">RSS</a> | <a href=\"{$url}{$info->username}/tags/\">" . gettext("Tags") . "</a> | <a href=\"{$url}{$info->username}/feeds/\">" . gettext("Resources") . "</a>";
					$usermenu = run("users:infobox:menu:text",array($info->ident));
				}
				$body .= <<< END
		<li>
			<a href="{$url}{$info->username}/">{$username}</a>
		</li>
END;
		
				if ($span == 1 || ($span == 2 && ($i % 2 == 0))) {
					$body .= "";
				}
				$i++;
			}
			$body .= "";
			
			if (isset($parameter[2]) && $parameter[2] != "") {
				$body .= "<li><p>" . $parameter[2] . "</p></li>";
			}
			
			$body .= "</ul>";
		}
		
			$run_result .= run("templates:draw", array(
						'context' => 'sidebarholder',
						'title' => $name,
						'body' => $body,
					)
					);
		
	}

?>