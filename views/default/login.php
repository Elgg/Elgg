<?php

     /**
	 * Elgg login form
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */
	 
?>

	<div id="login-box">
		<form action="<?php echo $vars['url']; ?>action/login" method="POST">
			<label>Username<br /><input name="username" type="text" class="general-textarea" /></label>
			<br />
			<label>Password<br /><input name="password" type="password" class="general-textarea" /></label><br />
			<input type="submit" name="submit" value="login" />
	    </form>
	</div>