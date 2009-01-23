<?php

	/**
	 * Elgg long text input
	 * Displays a long text input field
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 * 
	 * @uses $vars['value'] The current value, if any
	 * @uses $vars['js'] Any Javascript to enter into the input tag
	 * @uses $vars['internalname'] The name of the input field
	 * 
	 */

	$class = $vars['class'];
	if (!$class) $class = "input-textarea";
	
?>

<textarea class="<?php echo $class; ?>" name="<?php echo $vars['internalname']; ?>" <?php if ($vars['disabled']) echo ' disabled="yes" '; ?> <?php echo $vars['js']; ?>><?php echo $vars['value']; ?></textarea> 