<?php
/**
 * Discussion topic reply form body
 *
 * @uses $vars['entity'] A discussion topic object
 * @uses $vars['inline'] Display a shortened form?
 */

if (isset($vars['topic']) && elgg_is_logged_in()) {
	echo elgg_view('input/hidden', array(
		'name' => 'topic_guid',
		'value' => $vars['topic']->getGUID(),
	));

	$inline = elgg_extract('inline', $vars, false);

	$reply = elgg_extract('entity', $vars);

	$value = '';

	if ($reply) {
		$value = $reply->description;
		echo elgg_view('input/hidden', array(
			'name' => 'guid',
			'value' => $reply->guid
		));
	}

	if ($inline) {
		echo elgg_view('input/text', array(
			'name' => 'description',
			'value' => $value
		));
		echo elgg_view('input/submit', array(
			'value' => elgg_echo('reply')
		));
	} else {
?>
	<div>
		<label>
		<?php
			if ($reply) {
				echo elgg_echo('edit');
			} else {
				echo elgg_echo("reply");
			}
		?>
		</label>
		<?php
		echo elgg_view('input/longtext', array(
			'name' => 'description',
			'value' => $value
		));
		?>
	</div>
	<div class="elgg-foot">
<?php
	if ($reply) {
		echo elgg_view('input/submit', array('value' => elgg_echo('save')));
	} else {
		echo elgg_view('input/submit', array('value' => elgg_echo('reply')));
	}
?>
	</div>
<?php
	}
}
