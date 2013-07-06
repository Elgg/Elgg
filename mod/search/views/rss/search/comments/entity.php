<?php
/**
 * Search comment view for RSS feeds.
 *
 * @uses $vars['entity']
 */

$entity = $vars['entity'];
$comments_data = $entity->getVolatileData('search_comments_data');
$comment_data = array_shift($comments_data);
$entity->setVolatileData('search_comments_data', $comments_data);

$author_name = '';
$comment_author_guid = $comment_data['owner_guid'];
$author = get_user($comment_author_guid);
if ($author) {
	$author_name = $author->name;
}

// @todo Sometimes we find comments on entities we can't display...
if ($entity->getVolatileData('search_unavailable_entity')) {
	$title = elgg_echo('search:comment_on', array(elgg_echo('search:unavailable_entity')));
} else {
	if ($entity->getType() == 'object') {
		$title = $entity->title;
	} else {
		$title = $entity->name;
	}

	if (!$title) {
		$title = elgg_echo('item:' . $entity->getType() . ':' . $entity->getSubtype());
	}

	if (!$title) {
		$title = elgg_echo('item:' . $entity->getType());
	}

	$title = elgg_echo('search:comment_on', array($title));
	$title .= ' ' . elgg_echo('search:comment_by') . ' ' . $author_name;
	$url = $entity->getURL() . '#annotation-' . $comment_data['annotation_id'];
}

$description = $comment_data['text'];
$tc = $comment_data['time_created'];

?>

<item>
	<guid isPermaLink='true'><?php echo htmlspecialchars($url); ?></guid>
	<pubDate><?php echo date("r", $tc) ?></pubDate>
	<link><?php echo htmlspecialchars($url); ?></link>
	<title><![CDATA[<?php echo $title; ?>]]></title>
	<description><![CDATA[<?php	echo $description; ?>]]></description>
</item>
