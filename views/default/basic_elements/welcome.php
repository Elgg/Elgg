<?php

    /**
	 * Elgg sample welcome page
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */
	 
	 $user = $vars['name'];
	 
?>

<p>Welcome <?php echo $user; ?></p>
<p><a href="<?php echo $vars['url']; ?>action/logout">[logout]</a></p>