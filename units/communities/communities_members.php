<?php

	// Given a user ID as a parameter, will display a list of communities

	$url = url;
	
	if (isset($parameter[0])) {

		$user_id = (int) $parameter[0];
		
		$result = db_query("select users.*, friends.ident as friendident from friends
									left join users on users.ident = friends.owner
									where friends.friend = $user_id and users.user_type = 'person'");
									
		$body = <<< END
	<table class="userlist">
		<tr>
END;
		$i = 1;
		if (sizeof ($result) > 0) {
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
			<a href="{$url}{$friends_username}/">
			<img src="{$url}_icons/data/{$icon}" width="{$width}" height="{$height}" alt="{$friends_name}" border="0" /></a><br />
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
		} else {
				$body .= "<td>This community doesn't currently have any members.</td>";
		}
		$body .= <<< END
	</tr>
	</table>
END;


		$run_result = $body;

	}

?>