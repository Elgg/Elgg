<?php

    /**
	 * Elgg sample welcome page
	 * 
	 * @package Elgg
	 * @subpackage Core

	 * @author Curverider Ltd

	 * @link http://elgg.org/
	 */
	 
	 $user = $vars['name'];
	 
?>

<p><?php echo sprintf(elgg_echo('welcome:user'), $user); ?></p>
<p><a href="<?php echo $vars['url']; ?>action/logout">[logout]</a></p>