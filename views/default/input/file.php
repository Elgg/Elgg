<?php

	/**
	 * Elgg file input
	 * Displays a file input field
	 * 
	 * @package Elgg
	 * @subpackage Core

	 * @author Curverider Ltd

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
<input type="file" size="30" <?php echo $vars['js']; ?> name="<?php echo $vars['internalname']; ?>" <?php if (isset($vars['internalid'])) echo "id=\"{$vars['internalid']}\""; ?> <?php if ($vars['disabled']) echo ' disabled="yes" '; ?> class="<?php echo $class; ?>" />