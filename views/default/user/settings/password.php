<?php
	/**
	 * Provide a way of setting your password
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	$user = $_SESSION['user'];
	
	if ($user) {
?>
	<h2><?php echo elgg_echo('user:set:password'); ?></h2>
	<p>
		<?php echo elgg_echo('user:password:label'); ?> : <input type="password" name="password" value="" />
		<?php echo elgg_echo('user:password2:label'); ?> : <input type="password" name="password2" value="" />
	</p>

<?php } ?>