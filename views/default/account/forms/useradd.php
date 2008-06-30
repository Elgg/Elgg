<?php
	/**
	 * Elgg add user form. 
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	$admin_option = false;
	if (($_SESSION['user']->admin) && ($vars['show_admin'])) 
		$admin_option = true;
?>

	<h2><?php echo elgg_echo('adduser'); ?></h2>
	<div id="add-box">
		<form action="<?php echo $vars['url']; ?>action/useradd" method="POST">
			<p><label><?php echo elgg_echo('name'); ?>: <input name="name" type="text" class="general-textarea" /></label><br />
			<label><?php echo elgg_echo('email'); ?>: <input name="email" type="text" class="general-textarea" /></label><br />
			<label><?php echo elgg_echo('username'); ?>: <input name="username" type="text" class="general-textarea" /></label><br />
			<label><?php echo elgg_echo('password'); ?>: <input name="password" type="password" class="general-textarea" /></label><br />
			<label><?php echo elgg_echo('passwordagain'); ?>: <input name="password2" type="password" class="general-textarea" /></label><br />
			<?php
				if ($admin_option) {
?>		
			<label><?php echo elgg_echo('admin_option'); ?> <input type="checkbox" name="admin" /></label>	<br />		
<?php 			
				}
			 ?>
			<input type="submit" name="submit" class="submit_button" value="<?php echo elgg_echo('adduser'); ?>" /></p>
	    </form>
	</div>