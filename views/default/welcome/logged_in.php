<?php

	/**
	 * Elgg sample welcome page (logged in)
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

?>

	<h2><?php echo sprintf(elgg_echo("welcome"),$vars['user']->username); ?></h2>
	<p>
		<?php echo elgg_echo("welcome_message"); ?>
	</p>
	<p>
		<a href="<?php echo $vars['url']; ?>action/logout"><?php echo elgg_echo('logout'); ?></a>
	</p>