<table border="0" cellspacing="0" cellpadding="0" width="100%" class="banner">
  <tr>
     <td width="23" class="leftSpace">
	    &nbsp;
     </td>
	 <td width="190" class="sideLinks" valign="top">
		<table border="0" cellspacing="0" cellpadding="0" width="100%">
			<tr>
				<td class="sideLinksContent">
					<a href="run.php">Run your own Elgg</a>
				</td>
			</tr>
			<tr>
				<td class="sideLinksContent">
					<a href="privacy.php">Privacy Policy</a>
				</td>
			</tr>
			<tr>
				<td class="sideLinksContent">
					<a href="about.php">About</a>
				</td>
			</tr>
			<tr>
				<td class="sideLinksContent">
					<a href="faq.php">FAQ</a>
				</td>
			</tr>
		</table>
     </td>
	<td class="underline"> 
	   &nbsp;
    </td>
 </tr>
 </table>
<table border="0" cellspacing="0" cellpadding="0" width="100%" class="footer">
  <tr>
     <td width="23" class="leftSpace">
	    &nbsp;
     </td>
	 <td width="190" align="center" class="creative">
		 <a href="http://creativecommons.org/"><img src="creative.gif" border="1"></a>
     </td>
	 <td align="right" class="login">
		<form action="/_users/action_redirection.php" method="post" style="margin: 0px; padding: 2px; padding-left: 3%" id="login">
<?php
		global $messages;
		if (isset($messages) && sizeof($messages) > 0) {
			foreach($messages as $message) {
				echo "<span style=\"color: #ff0000; font-weight: bold\">" . $message . "</span><br />";
			}
		}
?>
						<b>Login ::</b>&nbsp;username&nbsp;<input type="text" name="username" id="username" value="" style="font-family: Verdana, arial, helv, helvetica, sans-serif; font-size: 80%" />&nbsp;password&nbsp;<input type="password" name="password" value="" style="font-family: Verdana, arial, helv, helvetica, sans-serif; font-size: 80%" /><input type="hidden" name="action" value="log_on" />&nbsp;<input type="submit" value="&gt;&gt;" style="font-family: Verdana, arial, helv, helvetica, sans-serif; font-size: 80%" />
		</form>
    </td>
 </tr>
 </table>
 </body>
 </html>