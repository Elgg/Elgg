<?php
/**
 * Reply header
 */

$post = $vars['post'];
$poster = $post->getOwnerEntity();
$poster_details = [
	htmlspecialchars($poster->getDisplayName(),  ENT_QUOTES, 'UTF-8'),
	htmlspecialchars($poster->username,  ENT_QUOTES, 'UTF-8'),
];
?>
<b><?php echo elgg_echo('thewire:replying', $poster_details); ?>: </b>
<?php echo $post->description;
