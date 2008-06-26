<?php

     /**
	 * Elgg register form
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */
	 
	$admin_option = false;
	if (($_SESSION['user']->admin) && ($vars['show_admin'])) 
		$admin_option = true;
?>

	<h2><?php echo elgg_echo('register'); ?></h2>
	<div id="register-box">
		<form action="<?php echo $vars['url']; ?>action/register" method="POST">
			<p><label><?php echo elgg_echo('name'); ?><br /><input name="name" type="text" class="general-textarea" /></label><br />
			<label><?php echo elgg_echo('email'); ?><br /><input name="email" type="text" class="general-textarea" /></label><br />
			<label><?php echo elgg_echo('username'); ?><br /><input name="username" type="text" class="general-textarea" /></label><br />
			<label><?php echo elgg_echo('password'); ?><br /><input name="password" type="password" class="general-textarea" /></label><br />
			<label><?php echo elgg_echo('passwordagain'); ?><br /><input name="password2" type="password" class="general-textarea" /></label><br />
			<?php
				if ($admin_option) {
?>		
			<label><?php echo elgg_echo('admin_option'); ?><br /><input type="checkbox" name="admin" /></label>			
<?php 			
				}
			 ?>
			<input type="submit" name="submit" class="submit_button" value="<?php echo elgg_echo('register'); ?>" /></p>
			<input type="hidden" name="action" value="register" />
	    </form>
	</div>