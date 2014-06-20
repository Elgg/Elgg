<?php
/**
 * River item footer
 *
 * @uses $vars['item'] ElggRiverItem
 * @uses $vars['responses'] Alternate override for this item
 */

// allow river views to override the response content
$responses = elgg_extract('responses', $vars, false);
if ($responses) {
	echo $responses;
	return true;
}

$item = $vars['item'];
/* @var ElggRiverItem $item */
$object = $item->getObjectEntity();
$target = $item->getTargetEntity();

// annotations and comments do not have responses
if ($item->annotation_id != 0 || !$object || elgg_instanceof($target, 'object', 'comment')) {
	return true;
}

/* @var \Elgg\ViewsService $this */

// To save queries we don't want to render comments until we know all the object(s) we'll
// be fetching them for. Here we tell our model to look out for these.
$model = _elgg_services()->riverComments;
$model->prepareLatestComments($object->guid);
$model->prepareNumComments($object->guid);

// Output a unique token that will later be replaced by the rendered view. At that time, our
// model will fetch our comments and counts in fewer queries.
echo $this->deferView('river/elements/responses/content', array(
	'model' => $model,
	'entity' => $object,
));

// inline comment form
$form_vars = array('id' => "comments-add-{$object->getGUID()}", 'class' => 'hidden');
$body_vars = array('entity' => $object, 'inline' => true);
echo elgg_view_form('comment/save', $form_vars, $body_vars);