<?php

	/**
	 * Elgg .htaccess not found message
	 * Is saved to the errors register when the main .htaccess cannot be found
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

?>
Elgg requires a file called .htaccess to be set in the root directory of its installation. We tried to create it for you, but Elgg doesn't have permission to write to that directory. 

Creating this is easy. Just take the htaccess_dist file in the root directory and rename it to .htaccess.  (On Windows systems, you will need to open htaccess_dist in Notepad and save it as .htaccess from there.)