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

	
	<div id="add-box">
	<h2><?php echo elgg_echo('adduser'); ?></h2>
		<form action="<?php echo $vars['url']; ?>action/useradd" method="POST">
			<p><label><?php echo elgg_echo('name'); ?>:
			<?php echo elgg_view('input/text', array('internalname' => 'name')); ?></label><br />
			<label><?php echo elgg_echo('email'); ?>: 
			<?php echo elgg_view('input/email', array('internalname' => 'email')); ?></label><br />
			<label><?php echo elgg_echo('username'); ?>:
			<?php echo elgg_view('input/text', array('internalname' => 'username')); ?></label><br />
			<label><?php echo elgg_echo('password'); ?>: 
			<?php echo elgg_view('input/password', array('internalname' => 'password')); ?></label><br />
			<label><?php echo elgg_echo('passwordagain'); ?>:
			<?php echo elgg_view('input/password', array('internalname' => 'password2')); ?></label><br />
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