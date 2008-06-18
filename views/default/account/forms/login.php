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
	<h2><?php echo elgg_echo('login'); ?></h2>
	<div id="login-box">
		<form action="<?php echo $vars['url']; ?>action/login" method="POST">
			<p><label><?php echo elgg_echo('username'); ?><br /><input name="username" type="text" class="general-textarea" /></label>
			<br />
			<label><?php echo elgg_echo('password'); ?><br /><input name="password" type="password" class="general-textarea" /></label><br />
			<input type="submit" name="submit" class="submit_button" value="<?php echo elgg_echo('login'); ?>" /></p>
			<p><a href="<?php echo $vars['url']; ?>register.php"><?php echo elgg_echo('register'); ?></a></p>
	    </form>
	</div>