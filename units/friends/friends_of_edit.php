<?php

	// Given a user ID as a parameter, will display a list of friends

	$url = url;
	
	if (isset($parameter[0])) {

		$user_id = (int) $parameter[0];
		
		$result = db_query("select users.* from friends
									left join users on users.ident = friends.owner
									where friend = $user_id and users.user_type = 'person'");

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
					$result = @getimagesize(path . "_icons/data/" . $icon);
					if ($result != false) {
						list($width, $height, $type, $attr) = $result;
						if (sizeof($parameter[1]) > 4) {
							$width = round($width / 2);
							$height = round($height / 2);
						}
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
			if ($user_id == $_SESSION['userid']) {
				$body .=  "<td>Nobody's listed you as a friend! Maybe you need to start chatting to some other users?</td>";
			} else {
				$body .= "<td>This user isn't currently listed as anyone's friend. Maybe you could be the first?</td>";
			}
		}
		$body .= <<< END
	</tr>
	</table>
END;
		}

	$run_result .= $body;
		
?>