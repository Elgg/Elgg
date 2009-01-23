<?php

	/**
	 * Elgg .htaccess not found message
	 * Is saved to the errors register when the main .htaccess cannot be found
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */

	echo autop(elgg_echo('installation:error:htaccess'));
?>
<textarea cols="120" rows="30"><?php echo $vars['.htaccess']; ?></textarea>