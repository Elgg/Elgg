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
	 
	global $CONFIG;
?>
	
	<div id="login-box">	<h2><?php echo elgg_echo('login'); ?></h2>
		<form action="<?php echo $vars['url']; ?>action/login" method="POST">
			<p><label><?php echo elgg_echo('username'); ?><br /><input name="username" type="text" class="general-textarea" /></label>
			<br />
			<label><?php echo elgg_echo('password'); ?><br /><input name="password" type="password" class="general-textarea" /></label><br />
			<input type="submit" name="submit" class="submit_button" value="<?php echo elgg_echo('login'); ?>" /></p>
			<p><?php if (!isset($CONFIG->disable_registration) || !($CONFIG->disable_registration)) { ?><a href="<?php echo $vars['url']; ?>account/register.php"><?php echo elgg_echo('register'); ?></a> : <?php } ?> <a href="<?php echo $vars['url']; ?>account/forgotten_password.php"><?php echo elgg_echo('user:password:lost'); ?></a></p>
	    </form>
	</div>