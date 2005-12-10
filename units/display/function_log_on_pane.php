<?php

	global $page_owner;
	$url = url;
		
	// If this is someone else's portfolio, display the user's icon
		if ($page_owner != -1) {
			$run_result .= run("profile:user:info");
		}

	if ((!defined("logged_on") || logged_on == 0) && $page_owner == -1) {

		$body = <<< END
		
		<form action="{$url}_users/action_redirection.php" method="post">
END;
		$body .= run("templates:draw",array(
						'template' => -1,
						'context' => 'contentholder',
						'title' => gettext("Log On"),
						'submenu' => '',
						'body' => '
			<table>
				<tr>
					<td align="right"><p>
						<label>' . gettext("Username") . '&nbsp;<input type="text" name="username" id="username" style="size: 200px" /></label><br />
						<label>' . gettext("Password") . '&nbsp;<input type="password" name="password" id="password" style="size: 200px" />
						</label></p>
					</td>
				</tr>
				<tr>
					<td align="right"><p>
						<input type="hidden" name="action" value="log_on" />
						<label>' . gettext("Log on") . ':<input type="submit" name="submit" value="'.gettext("Go").'" /></label><br /><br />
						<label><input type="checkbox" name="remember" checked="checked" />
								' . gettext("Remember Login") . '</label><br />
						<small>
							<a href="' . $url . '_invite/register.php">'. gettext("Register") .'</a> |
							<a href="' . $url . '_invite/forgotten_password.php">'. gettext("Forgotten password") .'</a>
						</small></p>
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
