<?php
/**
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 *
 * @uses $vars['body'] The content to display inside content wrapper
 * @uses $vars['subclass'] Additional css class
 *
 */
?>
<div class="contentWrapper<?php

	if (isset($vars['subclass'])) {
		echo ' ' . $vars['subclass'];
	}

?>">
<?php
	echo $vars['body'];
?>
</div>