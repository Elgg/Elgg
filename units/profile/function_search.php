<?php

	// Search criteria are passed in $parameter from run("search:display")
	
		$handle = 0;
		foreach($data['profile:details'] as $profiletype) {
			if ($profiletype[1] == $parameter[0] && $profiletype[2] == "keywords") {
				$handle = 1;
			}
		}
	
		if ($handle) {
			
			$searchline = "tagtype = '".addslashes($parameter[0])."' and tag = '".addslashes($parameter[1])."'";
			$searchline = "(" . run("users:access_level_sql_where",$_SESSION['userid']) . ") and " . $searchline;
			$result = db_query("select distinct users.* from tags left join users on users.ident = tags.owner where $searchline");

			$parameter[1] = stripslashes($parameter[1]);
			
			if (sizeof ($result) > 0) {
$body = <<< END
			
	<h2>
		Profiles where 
END;
				$body .= "'".$parameter[0]."' includes '".$parameter[1]."':";
				$body .= <<< END
	</h2>
END;
				$body .= <<< END
	<table class="userlist">
		<tr>
END;
				$i = 1;
				foreach($result as $key => $info) {
	
					// $info = $info[0];
					if ($info->icon != -1) {
						$icon = db_query("select filename from icons where ident = " . $info->icon . " and owner = " . $info->ident);
						if (sizeof($icon) == 1) {
							$icon = $icon[0]->filename;
						} else {
							$icon = "default.png";
						}
					} else {
						$icon = "default.png";
					}
					list($width, $height, $type, $attr) = getimagesize(path . "_icons/data/" . $icon);
					if (sizeof($parameter[1]) > 4) {
						$width = round($width / 2);
						$height = round($height / 2);
					}
					$friends_username = stripslashes($info->username);
					$friends_name = htmlentities(stripslashes($info->name));
					$friends_menu = run("users:infobox:menu",array($info->ident));
					$body .= <<< END
		<td align="center">
			<a href="/{$friends_username}/">
			<img src="/_icons/data/{$icon}" width="{$width}" height="{$height}" alt="{$friends_name}" border="0" /></a><br />
			<span class="userdetails">
				{$friends_name}
				{$friends_menu}
			</span>
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