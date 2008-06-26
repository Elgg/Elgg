<?php
	/**
	 * Provide a way of setting your email
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
	<h2><?php echo elgg_echo('email:settings'); ?></h2>
	<p>
		<?php echo elgg_echo('email:address:label'); ?> : <input type="text" name="email" value="<?php echo $user->email; ?>" />
	</p>

<?php } ?>