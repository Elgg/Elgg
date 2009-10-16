<?php
/**
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 *
 * @uses $vars['value'] The URL to display
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