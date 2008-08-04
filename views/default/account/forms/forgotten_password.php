<?php
	/**
	 * Elgg forgotten password.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */
?>
<div id="forgotten_box">
	<form action="<?php echo $vars['url']; ?>actions/user/requestnewpassword" method="post">
		<p><?php echo elgg_echo('user:password:text'); ?></p>
		<p><b><?php echo elgg_echo('username'); ?></b> <?php echo elgg_view('input/text', array('internalname' => 'username')); ?></p>
		<p><input type="submit" name="submit" class="submit_button" value="<?php echo elgg_echo('request'); ?>" /></p>
	</form>
</div>