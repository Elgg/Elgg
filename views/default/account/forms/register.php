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
	 
?>

	<h2><?php echo elgg_echo('register'); ?></h2>
	<div id="register-box">
		<form action="<?php echo $vars['url']; ?>action/register" method="POST">
			<p><label><?php echo elgg_echo('name'); ?><br /><input name="name" type="text" class="general-textarea" /></label><br />
			<label><?php echo elgg_echo('email'); ?><br /><input name="email" type="text" class="general-textarea" /></label><br />
			<label><?php echo elgg_echo('username'); ?><br /><input name="username" type="text" class="general-textarea" /></label><br />
			<label><?php echo elgg_echo('password'); ?><br /><input name="password" type="password" class="general-textarea" /></label><br />
			<label><?php echo elgg_echo('passwordagain'); ?><br /><input name="password" type="password" class="general-textarea" /></label><br />
			<input type="submit" name="submit" value="<?php echo elgg_echo('register'); ?>" /></p>
	    </form>
	</div>