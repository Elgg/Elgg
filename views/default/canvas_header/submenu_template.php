<?php
/**
 * Elgg default layout
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

if (isset($vars['selected']) && $vars['selected'] == true) {
	$selected = "class=\"selected\"";
} else {
	$selected = "";
}

if (isset($vars['onclick']) && $vars['onclick'] == true) {
	$onclick = "onclick=\"javascript:return confirm('". elgg_echo('deleteconfirm') . "')\"";
} else {
	$onclick = "";
}

?>
<li <?php echo $selected; ?>><a href="<?php echo $vars['href']; ?>" <?php echo $onclick; ?>><?php echo $vars['label']; ?></a></li>