<?php
/**
 * Reply header
 */

$post = $vars['post'];
$poster = $post->getOwnerEntity();

?>
<b><?php echo elgg_echo('thewire:replying', array($poster->name)); ?>: </b>
<?php echo $post->description;