<?php

	/**
	 * Elgg file input
	 * Displays a file input field
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 * 
	 * @uses $vars['js'] Any Javascript to enter into the input tag
	 * @uses $vars['internalname'] The name of the input field
	 * 
	 */

    if (!empty($vars['value'])) {
        echo elgg_echo('fileexists') . "<br />";
    }

    $class = $vars['class'];
	if (!$class) $class = "input-file";
?>
<input type="file" size="30" <?php echo $vars['js']; ?> name="<?php echo $vars['internalname']; ?>" <?php if ($vars['disabled']) echo ' disabled="yes" '; ?> class="<?php echo $class; ?>" />