<?php

	global $page_owner;
		
	// If this is someone else's portfolio, display the user's icon
		$run_result .= "<div class=\"box_user\">";
		if ($page_owner != -1) {
			if ($page_owner != $_SESSION['userid']) {
				$run_result .= run("users:infobox", array("Profile Owner",array($page_owner)));
			} else {
				$run_result .= run("users:infobox", array("You",array($page_owner)));
			}
		}
		$run_result .= "</div>";

	if ((!defined("logged_on") || logged_on == 0) && $page_owner == -1) {

		$body = <<< END
		
		<form action="/_users/action_redirection.php" method="post">
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
						<input type="hidden" name="action" value="log_on" />
						<label>Log on: <input type="submit" name="submit" value="Go" /></label><br />
						<small><a href="/_invite/register.php">Register</a></small>
					</td>
				</tr>
			
			</table>

'
					)
					);
		$body .= "</form>";
/*		$body .= run("templates:draw",array(
						'template' => -1,
						'context' => 'infobox',
						'name' => 'Or, Register ...',
						'contents' => '
			<table>
				<tr>
					<td>
						Registration is disabled for the time being. If you would
						like to be notified when Elgg is open to outside users, please 
						<a href="/">fill in the form on the front page</a>.
					</td>
				</tr>
<!--				<tr>
					<td>
						<label for="regusername">Username</label>
					</td>
					<td align="right">
						<input type="text" name="username" id="regusername" style="size: 200px" />
					</td>
				</tr>
				<tr>
					<td>
						<label for="email">Email address</label>
					</td>
					<td align="right">
						<input type="text" name="email" id="email" style="size: 200px" />
					</td>
				</tr>
				<tr>
					<td>
						<label for="password1">Password</label>
					</td>
					<td align="right">
						<input type="password" name="password1" id="password1" style="size: 200px" />
					</td>
				</tr>
				<tr>
					<td>
						<label for="password2">Password again for verification</label>
					</td>
					<td align="right">
						<input type="password" name="password2" id="password2" style="size: 200px" />
					</td>
				</tr>
				<tr>
					<td colspan="2" align="right">
						<input type="hidden" name="action" value="register" />
						<label>Register: <input type="submit" name="submit" value="Go" /></label>
					</td>
				</tr> -->
			
			</table>
'
			)
			);
*/
		$run_result .= $body;
			
	}

?>
