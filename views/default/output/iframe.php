<?php
/**
 * Display a page in an embedded window
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['value'] Source of the page
 *
 */
?>
<iframe src="<?php echo $vars['value']; ?>">
</iframe>