<?php
/**
 * Elgg error message
 * Displays a single error message
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['object'] An error message (string)
 */
?>

<p>
<?php 
	echo elgg_view('output/longtext', array(
		'value' => $vars['object'],
		'parse_urls' => FALSE));
?>
</p>