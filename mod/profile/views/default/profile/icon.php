<?php

	/**
	 * Elgg profile icon
	 * 
	 * @package ElggProfile
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Ben Werdmuller <ben@curverider.co.uk>
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 * 
	 * @uses $vars['entity'] The user entity. If none specified, the current user is assumed.
	 * @uses $vars['size'] The size - small, medium or large. If none specified, medium is assumed. 
	 */

	// Get entity
		if (empty($vars['entity']))
			$vars['entity'] = $vars['user'];

		$name = htmlentities($vars['entity']->name);
		$username = $vars['entity']->username;
			
	// Get size
		if (!in_array($vars['size'],array('small','medium','large')))
			$vars['size'] = "medium";
			
	// Get any align and js
		if (!empty($vars['align'])) {
			$align = " align=\"{$vars['align']}\" ";
		} else {
			$align = "";
		}
			
?>

	<a href="<?php echo $vars['entity']->getURL(); ?>"><img src="<?php echo $vars['url']; ?>pg/icon/<?php echo $username; ?>/<?php echo $vars['size']; ?>/icon.jpg" border="0" <?php echo $align; ?> title="<?php echo $name; ?>" <?php echo $vars['js']; ?> /></a>