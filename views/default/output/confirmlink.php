<?php

	/**
	 * Elgg confirmation link
	 * A link that displays a confirmation dialog before it executes
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 * 
	 * @uses $vars['text'] The text of the link
	 * @uses $vars['href'] The address
	 * @uses $vars['confirm'] The dialog text
	 * 
	 */

	$confirm = $vars['confirm'];
	if (!$confirm)
		$confirm = elgg_echo('question:areyousure');
?>
<a href="<?php echo $vars['href']; ?>" onclick="return confirm('<?php echo addslashes($confirm); ?>');"><?php echo htmlentities($vars['text'], ENT_QUOTES, 'UTF-8'); ?></a>