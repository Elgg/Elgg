<?php
/**
 * Wrapper for profile content
 *
 * @uses string $vars['content'] - Profile body
 */

$content = elgg_get_array_value('content', $vars, '');

?>
<div id="profile_content">
	<?php echo $content; ?>
</div>