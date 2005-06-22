<?php

	global $page_owner;
	$url = url;
		
	// If this is someone else's portfolio, display the user's icon
		$run_result .= "<div class=\"box_user\">";
		if ($page_owner != -1) {
			// $rsslink = "(<a href=\"". url . run("users:id_to_name",$page_owner) . "/rss/\">RSS</a>)";
			if ($page_owner != $_SESSION['userid']) {
				$run_result .= run("users:infobox", array("Profile Owner",array($page_owner)));
			} else {
				$run_result .= run("users:infobox", array("You",array($page_owner)));
			}
		}
		$run_result .= "</div>";

	if ((!defined("logged_on") || logged_on == 0) && $page_owner == -1) {

		$body = <<< END
		
		<form action="{$url}_users/action_redirection.php" method="post">
END;
		$body .= run("templates:draw",array(
						'template' => -1,
						'context' => 'infobox',
						'name' => 'Log On',
						'contents' => '
			<table>
				<tr>
					<td align="right">
						<label>Username&nbsp;<input type="text" name="username" id="username" style="size: 200px" />
						</label>
					</td>
				</tr>
				<tr>
					<td align="right">
						<label>Password&nbsp;<input type="password" name="password" id="password" style="size: 200px" />
						</label>
					</td>
				</tr>
				<tr>
					<td align="right">
                                                <label><input type="checkbox" name="remember" checked="checked" /> Remember Login</label>
					</td>
				</tr>
				<tr>
					<td align="right">
						<input type="hidden" name="action" value="log_on" />
						<label>Log on: <input type="submit" name="submit" value="Go" /></label><br />
						<small>
							<a href="' . $url . '_invite/register.php">Register</a> |
							<a href="' . $url . '_invite/forgotten_password.php">Forgotten password</a>
						</small>
					</td>
				</tr>
			
			</table>

'
					)
					);
		$body .= "</form>";

		$run_result .= $body;
			
	}

?>
