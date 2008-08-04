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
	
		<?php echo elgg_echo('user:language:label'); ?>: <?php

			$value = $CONFIG->language;
			if ($user->language)
				$value = $user->language;
			
			echo elgg_view("input/pulldown", array('internalname' => 'language', 'value' => $value, 'options_values' => get_installed_translations()));
		
		 ?> 

	</p>

<?php } ?>