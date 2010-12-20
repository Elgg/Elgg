<?php
/**
 * Elgg confirmation link
 * A link that displays a confirmation dialog before it executes
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['text'] The text of the link
 * @uses $vars['href'] The address
 * @uses $vars['confirm'] The dialog text
 * @uses $vars['encode'] Encode special characters?
 */

$confirm = elgg_get_array_value('confirm', $vars, elgg_echo('question:areyousure'));

$encode = elgg_get_array_value('encode', $vars, true);

// always generate missing action tokens
$link = elgg_add_action_tokens_to_url(elgg_normalize_url($vars['href']));

$text = elgg_get_array_value('text', $vars, '');
if ($encode) {
	$text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

if (isset($vars['class']) && $vars['class']) {
	$class = 'class="' . $vars['class'] . '"';
} else {
	$class = '';
}
?>
<a href="<?php echo $link; ?>" <?php echo $class; ?> onclick="return confirm('<?php echo addslashes($confirm); ?>');">
	<?php echo $text; ?>
</a>
