<?php
/**
 * Messages folder view (inbox, sent)
 *
 * Provides form body for mass deleting messages
 *
 * @uses $vars['list'] List of messages
 * 
 */

echo $vars['list'];

echo '<div class="messages-buttonbank">';
echo elgg_view('input/submit', array(
	'value' => elgg_echo('delete'),
	'internalname' => 'delete',
));

if ($vars['folder'] == "inbox") {
	echo elgg_view('input/submit', array(
		'value' => elgg_echo('messages:markread'),
		'internalname' => 'read',
	));
}

echo elgg_view('input/button', array(
	'value' => elgg_echo('messages:toggle'),
	'class' => 'elgg-button-cancel',
	'internalid' => 'messages-toggle',
));

echo '</div>';

?>
<script type="text/javascript">
$(document).ready(function() {
	$("#messages-toggle").click(function() {
		$('input[type=checkbox]').click();
	});
});
</script>
