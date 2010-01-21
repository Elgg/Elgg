<?php
/**
 * Elgg action link
 * Creates a link to an action that includes action tokens
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 *
 * @uses $vars['text'] The text of the link
 * @uses $vars['href'] The address
 * @uses $vars['confirm'] The dialog text
 *
 */

// always generate missing action tokens
$link = elgg_validate_action_url($vars['href']);

if (isset($vars['class']) && $vars['class']) {
	$class = 'class="' . $vars['class'] . '"';
} else {
	$class = '';
}
?>
<a href="<?php echo $link; ?>" <?php echo $class; ?> ><?php echo htmlentities($vars['text'], ENT_QUOTES, 'UTF-8'); ?></a>