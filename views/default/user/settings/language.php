<?php
	/**
	 * Provide a way of setting your language prefs
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	global $CONFIG;
	$user = $_SESSION['user'];
	
	if ($user) {
?>
	<h2><?php echo elgg_echo('user:set:language'); ?></h2>
	<p>
		<?php echo elgg_echo('user:language:label'); ?> : <input type="text" name="language" value="<?php
			if ($user->language)
				echo $user->language;
			else
				echo $CONFIG->language;
		 ?>" />
	</p>

<?php } ?>