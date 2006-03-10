<?php

	// Search criteria are passed in $parameter from run("search:display")
	
		$url = url;
		$handle = 0;
		foreach($data['profile:details'] as $profiletype) {
			if ($profiletype[1] == $parameter[0] && $profiletype[2] == "keywords") {
				$handle = 1;
			}
		}
	
		if ($handle) {
			
			$searchline = "tagtype = '".addslashes($parameter[0])."' and tag = '".addslashes($parameter[1])."'";
			$searchline = "(" . run("users:access_level_sql_where",$_SESSION['userid']) . ") and " . $searchline;
			$searchline = str_replace("owner","tags.owner",$searchline);
			$result = db_query("select distinct users.* from tags join users on users.ident = tags.owner where $searchline");

			$parameter[1] = stripslashes($parameter[1]);
			
			if (sizeof ($result) > 0) {
				$profilesMsg = gettext("Profiles where");
				$body = <<< END
			
	<h2>
		$profilesMsg
END;
				$body .= " '".gettext($parameter[0])."' = '".$parameter[1]."':";
				$body .= <<< END
	</h2>
END;
				$body .= <<< END
	<table class="userlist">
		<tr>
END;
				$i = 1;
				$icon = "default.png";
				$defaulticonparams = @getimagesize(path . "_icons/data/default.png");
				
				foreach($result as $key => $info) {
					
					list($width, $height, $type, $attr) = $defaulticonparams;
					// $info = $info[0];
					// if ($info->icon != -1) {
						$icon = db_query("select filename from icons where ident = " . $info->icon);
						if (sizeof($icon) == 1) {
							$icon = $icon[0]->filename;
							if (!(list($width, $height, $type, $attr) = @getimagesize(path . "_icons/data/" . $icon))) {
								$icon = "default.png";
								list($width, $height, $type, $attr) = $defaulticonparams;
							}
						} else {
							$icon = "default.png";
						}
					// }
					
					if (sizeof($parameter[1]) > 4) {
						$width = round($width / 2);
						$height = round($height / 2);
					}
					$friends_username = stripslashes($info->username);
					$friends_name = htmlentities(stripslashes($info->name));
					$friends_menu = run("users:infobox:menu",array($info->ident));
					$width = round($width / 2);
					$height = round($height / 2);
					$body .= <<< END
		<td align="center">
			<p>
			<a href="{$url}{$friends_username}/">
			<img src="{$url}_icons/data/{$icon}" width="{$width}" height="{$height}" alt="{$friends_name}" border="0" /></a><br />
			<span class="userdetails">
				{$friends_name}
				{$friends_menu}
			</span>
			</p>
		</td>
END;
					if ($i % 5 == 0) {
						$body .= "</tr><tr>";
					}
					$i++;
				}
				$body .= <<< END
	</tr>
	</table>
END;
				$run_result .= $body;
			}
		}

?>