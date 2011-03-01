<?php
/**
 * Wire add form body
 *
 * @uses $vars['post']
 */

$post = elgg_extract('post', $vars);

$text = elgg_echo('post');
if ($post) {
	$text = elgg_echo('thewire:reply');
}

if ($post) {
	echo elgg_view('input/hidden', array(
		'name' => 'parent_guid',
		'value' => $post->guid,
	));
}
?>
<textarea id="thewire-textarea" name="body" class="mtm"></textarea>
<div id="thewire-characters-remaining">
	<span>140</span> <?php echo elgg_echo('thewire:charleft'); ?>
</div>
<div class="mts">
<?php

echo elgg_view('input/submit', array(
	'value' => $text,
	'id' => 'thewire-submit-button',
));

?>
</div>
<script type="text/javascript">

$(document).ready(function() {
	$("#thewire-textarea").bind('keydown', function() {
		textCounter(this, $("#thewire-characters-remaining span"), 140);
	});
	$("#thewire-textarea").bind('keyup', function() {
		textCounter(this, $("#thewire-characters-remaining span"), 140);
	});
});

function textCounter(textarea, status, limit) {

	var remaining_chars = limit - textarea.value.length;
	status.html(remaining_chars);

	if (remaining_chars < 0) {
		status.parent().css("color", "#D40D12");
		$("#thewire-submit-button").attr('disabled', 'disabled');
		$("#thewire-submit-button").css('background', '#999999');
		$("#thewire-submit-button").css('border-color', '#999999');
		$("#thewire-submit-button").css('cursor', 'default');
	} else {
		status.parent().css("color", "");
		$("#thewire-submit-button").removeAttr('disabled', 'disabled');
		$("#thewire-submit-button").css('background', '#4690d6');
		$("#thewire-submit-button").css('border-color', '#4690d6');
		$("#thewire-submit-button").css('cursor', 'pointer');
	}
}
</script>
