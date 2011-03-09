<?php
/**
 * Move text of first annotation to group forum topic object and delete annotation
 *
 * First determine if the upgrade is needed and then if needed, batch the update
 */

$topics = elgg_get_entities(array(
	'type' => 'object',
	'subtype' => 'groupforumtopic',
	'limit' => 5,
));

// if not topics, no upgrade required
if (!$topics) {
	return;
}

// if all five of the topics have empty descriptions, we need to upgrade
foreach ($topics as $topic) {
	if ($topic->description) {
		return;
	}
}


/**
 * Condense annotation into object
 *
 * @param ElggObject $topic
 */
function groups_2011030101($topic) {

	$annotation = $topic->getAnnotations('group_topic_post', 1);
	if (!$annotation) {
		// no text for this forum post so we delete (probably caused by #2624)
		return $topic->delete();
	}

	$topic->description = $annotation[0]->value;
	$topic->save();

	return $annotation[0]->delete();
}

$options = array('type' => 'object', 'subtype' => 'groupforumtopic');
$batch = new ElggBatch('elgg_get_entities', $options, 'groups_2011030101', 100);

if ($batch->callbackResult) {
	error_log("Elgg Groups upgrade (2011030101) succeeded");
} else {
	error_log("Elgg Groups upgrade (2011030101) failed");
}
