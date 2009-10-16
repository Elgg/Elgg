<?php
/**
 * Display a page in an embedded window
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 *
 * @uses $vars['value'] Source of the page
 *
 */
?>
<iframe src="<?php echo $vars['value']; ?>">
</iframe>