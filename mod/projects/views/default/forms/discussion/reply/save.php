<?php
/**
 * Discussion topic reply form body
 *
 * @uses $vars['entity'] A discussion topic object
 * @uses $vars['inline'] Display a shortened form?
 */

if (isset($vars['entity']) && elgg_is_logged_in()) {
	echo elgg_view('input/hidden', array(
		'name' => 'entity_guid',
		'value' => $vars['entity']->getGUID(),
	));

	$inline = elgg_extract('inline', $vars, false);

	$annotation = elgg_extract('annotation', $vars);
	
	$value = '';

	if ($annotation) {
		$value = $annotation->value;
		echo elgg_view('input/hidden', array(
			'name' => 'annotation_id',
			'value' => $annotation->id
		));
	}

	if ($inline) {
		echo elgg_view('input/text', array('name' => 'group_topic_post', 'value' => $value));
		echo elgg_view('input/submit', array('value' => elgg_echo('reply')));
	} else {
?>
	<div>
		<label>
		<?php
			if ($annotation) {
				echo elgg_echo('edit');
			} else {
				echo elgg_echo("reply");
			}
		?>
		</label>
		<?php echo elgg_view('input/longtext', array('name' => 'group_topic_post', 'value' => $value)); ?>
	</div>
	<div class="elgg-foot">
<?php
	if ($annotation) {
		echo elgg_view('input/submit', array('value' => elgg_echo('save')));
	} else {
		echo elgg_view('input/submit', array('value' => elgg_echo('reply')));
	}
?>
	</div>
<?php
	}
}
