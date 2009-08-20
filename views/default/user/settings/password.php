<?php
	/**
	 * Provide a way of setting your password
	 * 
	 * @package Elgg
	 * @subpackage Core

	 * @author Curverider Ltd

	 * @link http://elgg.org/
	 */

	$user = page_owner_entity();
	
	if ($user) {
?>
	<h3><?php echo elgg_echo('user:set:password'); ?></h3>
	<p>
		<?php echo elgg_echo('user:password:label'); ?>: 
		<?php
			echo elgg_view('input/password',array('internalname' => 'password'));
		?></p><p>
		<?php echo elgg_echo('user:password2:label'); ?>: <?php
			echo elgg_view('input/password',array('internalname' => 'password2'));
		?>
	</p>

<?php } ?>